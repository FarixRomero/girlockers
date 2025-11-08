<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class LessonForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('required|exists:modules,id')]
    public $module_id = '';

    #[Validate('required|exists:instructors,id')]
    public $instructor_id = '';

    #[Validate('nullable|array')]
    public array $selectedTags = [];

    #[Validate('nullable|image|max:10240')]
    public $thumbnail = null;

    public string $video_type = 'bunny';
    public string $bunny_video_id = '';
    public bool $is_trial = false;
    public int $duration = 0;

    #[Validate('nullable|integer|min:0')]
    public int $duration_minutes = 0;

    #[Validate('nullable|integer|min:0|max:59')]
    public int $duration_seconds = 0;

    // UI State
    public ?string $thumbnailPreview = null;
    public ?string $existingThumbnail = null;

    /**
     * Load lesson data into form
     *
     * @param \App\Models\Lesson $lesson
     * @return void
     */
    public function setLesson($lesson): void
    {
        $this->title = $lesson->title;
        $this->description = $lesson->description ?? '';
        $this->module_id = $lesson->module_id;
        $this->instructor_id = $lesson->instructor_id;
        $this->bunny_video_id = $lesson->bunny_video_id ?? '';
        $this->video_type = $lesson->video_type;
        $this->duration = $lesson->duration;

        // Convert duration from seconds to minutes and seconds
        $this->duration_minutes = floor($lesson->duration / 60);
        $this->duration_seconds = $lesson->duration % 60;

        $this->is_trial = $lesson->is_trial;
        $this->selectedTags = $lesson->tags->pluck('id')->toArray();

        if ($lesson->thumbnail) {
            $this->existingThumbnail = $lesson->thumbnail;
            $this->thumbnailPreview = $lesson->thumbnail_url;
        }
    }

    /**
     * Get form data as array for creating/updating lesson
     *
     * @param bool $isPublished
     * @return array
     */
    public function getData(bool $isPublished = true): array
    {
        // Calculate total duration in seconds from minutes and seconds
        $totalDuration = ($this->duration_minutes * 60) + $this->duration_seconds;

        return [
            'title' => $this->title,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'instructor_id' => $this->instructor_id,
            'video_type' => 'bunny',
            'bunny_video_id' => $this->bunny_video_id,
            'duration' => $totalDuration > 0 ? $totalDuration : $this->duration,
            'is_trial' => $this->is_trial,
            'is_published' => $isPublished,
        ];
    }

    /**
     * Update thumbnail preview when file is uploaded
     *
     * @return void
     */
    public function updateThumbnailPreview(): void
    {
        if ($this->thumbnail) {
            $this->thumbnailPreview = $this->thumbnail->temporaryUrl();
        }
    }
}
