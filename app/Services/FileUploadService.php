<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadService
{
    // Maximum dimensions for resizing
    private const MAX_WIDTH = 1920;
    private const MAX_HEIGHT = 1080;
    private const JPEG_QUALITY = 85;
    /**
     * Upload an image file to storage and optionally delete the existing file
     * Automatically resizes images if they exceed MAX_WIDTH or MAX_HEIGHT
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Target directory (e.g., 'lessons/thumbnails', 'courses', 'instructors')
     * @param string|null $existingPath Path to existing file to delete (optional)
     * @return string The path of the uploaded file
     * @throws \Exception
     */
    public function uploadImage(
        UploadedFile $file,
        string $directory,
        ?string $existingPath = null
    ): string {
        try {
            // Delete existing file if provided
            if ($existingPath) {
                $this->deleteFile($existingPath);
            }

            // Resize image if needed
            $image = Image::read($file->getRealPath());
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            $needsResize = false;

            // Check if image needs resizing
            if ($originalWidth > self::MAX_WIDTH || $originalHeight > self::MAX_HEIGHT) {
                $needsResize = true;

                // Scale down while maintaining aspect ratio
                $image->scale(
                    width: self::MAX_WIDTH,
                    height: self::MAX_HEIGHT
                );

                Log::info('Image resized', [
                    'original' => "{$originalWidth}x{$originalHeight}",
                    'new' => "{$image->width()}x{$image->height()}"
                ]);
            }

            // Encode image (optimize if resized)
            if ($needsResize) {
                $encoded = $image->toJpeg(quality: self::JPEG_QUALITY);
            } else {
                // Keep original format and quality if no resize needed
                $encoded = $image->encode();
            }

            // Generate unique filename
            $extension = $needsResize ? 'jpg' : $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $path = $directory . '/' . $filename;

            // Upload to S3
            $uploaded = Storage::disk('s3')->put($path, (string) $encoded, 'public');

            if (!$uploaded) {
                throw new \Exception('Failed to store file');
            }

            Log::info('File uploaded successfully', [
                'directory' => $directory,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'resized' => $needsResize,
                'final_size' => strlen((string) $encoded) . ' bytes'
            ]);

            return $path;

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a file from storage
     *
     * @param string $path Path to the file in S3
     * @return bool True if file was deleted, false if file didn't exist
     */
    public function deleteFile(string $path): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk('s3')->exists($path)) {
                $deleted = Storage::disk('s3')->delete($path);

                if ($deleted) {
                    Log::info('File deleted successfully', ['path' => $path]);
                }

                return $deleted;
            }

            Log::info('File not found for deletion', ['path' => $path]);
            return false;

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete multiple files from storage
     *
     * @param array $paths Array of file paths to delete
     * @return int Number of files successfully deleted
     */
    public function deleteFiles(array $paths): int
    {
        $deletedCount = 0;

        foreach ($paths as $path) {
            if ($this->deleteFile($path)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Check if a file exists in storage
     *
     * @param string $path Path to the file
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk('s3')->exists($path);
    }

    /**
     * Get the full URL for a stored file
     *
     * @param string|null $path Path to the file
     * @return string|null
     */
    public function getFileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk('s3')->url($path);
    }
}
