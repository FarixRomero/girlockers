<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonView extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'viewed_at',
        'completed_at',
        'progress_percentage',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Record or update a lesson view for a user
     */
    public static function recordView($userId, $lessonId)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
            ],
            [
                'viewed_at' => now(),
            ]
        );
    }

    /**
     * Mark lesson as completed for a user
     */
    public static function markAsCompleted($userId, $lessonId)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
            ],
            [
                'viewed_at' => now(),
                'completed_at' => now(),
                'progress_percentage' => 100,
            ]
        );
    }

    /**
     * Update lesson progress for a user
     */
    public static function updateProgress($userId, $lessonId, $percentage)
    {
        $view = self::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
            ],
            [
                'viewed_at' => now(),
                'progress_percentage' => $percentage,
            ]
        );

        // Mark as completed if progress reaches 90% or more
        if ($percentage >= 90 && !$view->completed_at) {
            $view->update(['completed_at' => now()]);
        }

        return $view;
    }

    /**
     * Check if lesson is completed
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
