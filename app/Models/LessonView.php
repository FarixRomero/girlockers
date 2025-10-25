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
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
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
}
