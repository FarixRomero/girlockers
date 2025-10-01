<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'content',
    ];

    /**
     * Get the lesson that owns the comment
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the user who authored the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
