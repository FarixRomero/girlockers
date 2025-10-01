<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    public function stream(Lesson $lesson)
    {
        // Verify user has access to this lesson
        Gate::authorize('view', $lesson);

        // Check if lesson has a local video
        if ($lesson->video_type !== 'local' || !$lesson->video_path) {
            abort(404, 'Video not found');
        }

        // Get the full path to the video file
        $path = Storage::disk('public')->path($lesson->video_path);

        if (!file_exists($path)) {
            abort(404, 'Video file not found');
        }

        $fileSize = filesize($path);
        $mime = Storage::disk('public')->mimeType($lesson->video_path) ?? 'video/mp4';

        // Get range header from request
        $range = request()->header('Range');

        if ($range) {
            // Parse range header (e.g., "bytes=0-1024")
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
            $start = intval($matches[1]);
            $end = !empty($matches[2]) ? intval($matches[2]) : $fileSize - 1;
            $length = $end - $start + 1;

            // Create streaming response with partial content
            $response = new StreamedResponse(function () use ($path, $start, $length) {
                $stream = fopen($path, 'rb');
                fseek($stream, $start);

                $buffer = 1024 * 8; // 8KB buffer
                $remaining = $length;

                while ($remaining > 0 && !feof($stream)) {
                    $read = min($buffer, $remaining);
                    echo fread($stream, $read);
                    $remaining -= $read;
                    flush();
                }

                fclose($stream);
            }, 206); // 206 Partial Content

            $response->headers->set('Content-Type', $mime);
            $response->headers->set('Content-Length', $length);
            $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$fileSize}");
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Cache-Control', 'public, max-age=3600');

        } else {
            // No range requested, stream entire file
            $response = new StreamedResponse(function () use ($path) {
                $stream = fopen($path, 'rb');
                fpassthru($stream);
                fclose($stream);
            }, 200);

            $response->headers->set('Content-Type', $mime);
            $response->headers->set('Content-Length', $fileSize);
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Cache-Control', 'public, max-age=3600');
        }

        return $response;
    }
}
