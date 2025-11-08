<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Module;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonService
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Create a new lesson
     *
     * @param array $data Lesson data
     * @param array $tags Tag IDs to attach
     * @param mixed $thumbnailFile Uploaded thumbnail file
     * @return Lesson
     * @throws \Exception
     */
    public function createLesson(array $data, array $tags = [], $thumbnailFile = null): Lesson
    {
        try {
            return DB::transaction(function () use ($data, $tags, $thumbnailFile) {
                // Get next order number
                $module = Module::findOrFail($data['module_id']);
                $nextOrder = $module->lessons()->max('order') + 1;

                // Handle thumbnail upload
                $thumbnailPath = null;
                if ($thumbnailFile) {
                    $thumbnailPath = $this->fileUploadService->uploadImage(
                        $thumbnailFile,
                        'lessons/thumbnails'
                    );
                }

                // Create lesson
                $lesson = Lesson::create([
                    ...$data,
                    'thumbnail' => $thumbnailPath,
                    'video_path' => null,
                    'youtube_id' => null,
                    'order' => $nextOrder,
                ]);

                // Attach tags
                if (!empty($tags)) {
                    $lesson->tags()->attach($tags);
                }

                Log::info('Lesson created successfully', [
                    'lesson_id' => $lesson->id,
                    'module_id' => $data['module_id']
                ]);

                return $lesson;
            });
        } catch (\Exception $e) {
            Log::error('Failed to create lesson', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing lesson
     *
     * @param Lesson $lesson
     * @param array $data Lesson data
     * @param array $tags Tag IDs to sync
     * @param mixed $thumbnailFile Uploaded thumbnail file (optional)
     * @param string|null $existingThumbnail Path to existing thumbnail (for deletion)
     * @return Lesson
     * @throws \Exception
     */
    public function updateLesson(
        Lesson $lesson,
        array $data,
        array $tags = [],
        $thumbnailFile = null,
        ?string $existingThumbnail = null
    ): Lesson {
        try {
            return DB::transaction(function () use ($lesson, $data, $tags, $thumbnailFile, $existingThumbnail) {
                // Handle thumbnail upload
                $thumbnailPath = $existingThumbnail;
                if ($thumbnailFile) {
                    $thumbnailPath = $this->fileUploadService->uploadImage(
                        $thumbnailFile,
                        'lessons/thumbnails',
                        $existingThumbnail
                    );
                }

                // Update lesson
                $lesson->update([
                    ...$data,
                    'thumbnail' => $thumbnailPath,
                ]);

                // Sync tags
                $lesson->tags()->sync($tags);

                Log::info('Lesson updated successfully', [
                    'lesson_id' => $lesson->id
                ]);

                return $lesson;
            });
        } catch (\Exception $e) {
            Log::error('Failed to update lesson', [
                'error' => $e->getMessage(),
                'lesson_id' => $lesson->id
            ]);
            throw $e;
        }
    }

    /**
     * Delete a lesson and its associated files
     *
     * @param Lesson $lesson
     * @return bool
     * @throws \Exception
     */
    public function deleteLesson(Lesson $lesson): bool
    {
        try {
            return DB::transaction(function () use ($lesson) {
                // Delete thumbnail if exists
                if ($lesson->thumbnail) {
                    $this->fileUploadService->deleteFile($lesson->thumbnail);
                }

                // Delete the lesson (tags will be detached automatically via cascade)
                $deleted = $lesson->delete();

                Log::info('Lesson deleted successfully', [
                    'lesson_id' => $lesson->id
                ]);

                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete lesson', [
                'error' => $e->getMessage(),
                'lesson_id' => $lesson->id
            ]);
            throw $e;
        }
    }
}
