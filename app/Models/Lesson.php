<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'video_type',
        'youtube_id',
        'video_path',
        'duration',
        'thumbnail',
        'is_trial',
        'order',
        'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'is_trial' => 'boolean',
            'order' => 'integer',
            'likes_count' => 'integer',
        ];
    }

    /**
     * Get the module that owns the lesson
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get lesson comments ordered by newest first
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Get users who liked this lesson
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_likes');
    }

    /**
     * Check if user can access this lesson
     */
    public function isAccessibleBy(User $user): bool
    {
        // Admins can access all lessons
        if ($user->isAdmin()) {
            return true;
        }

        // Trial lessons accessible to all authenticated users
        if ($this->is_trial) {
            return true;
        }

        // Premium lessons require full access
        return $user->hasFullAccess();
    }

    /**
     * Scope to get lessons accessible by a user
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->isAdmin() || $user->hasFullAccess()) {
            return $query;
        }

        return $query->where('is_trial', true);
    }

    /**
     * Check if user has liked this lesson
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Increment likes count
     */
    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

    /**
     * Decrement likes count
     */
    public function decrementLikes(): void
    {
        $this->decrement('likes_count');
    }
}
