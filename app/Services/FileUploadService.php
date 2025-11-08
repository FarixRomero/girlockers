<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload an image file to storage and optionally delete the existing file
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

            // Store the new file in the public disk
            $path = $file->store($directory, 'public');

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            Log::info('File uploaded successfully', [
                'directory' => $directory,
                'path' => $path,
                'original_name' => $file->getClientOriginalName()
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
     * @param string $path Path to the file relative to storage/app/public
     * @return bool True if file was deleted, false if file didn't exist
     */
    public function deleteFile(string $path): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                $deleted = Storage::disk('public')->delete($path);

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
        return Storage::disk('public')->exists($path);
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

        return Storage::disk('public')->url($path);
    }
}
