<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $duration Duration in seconds (auto-detected from Bunny.net or manually entered)
 * @property int $duration_minutes Computed attribute: duration in minutes (read-only)
 */
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'instructor_id',
        'title',
        'description',
        'video_type',
        'youtube_id',
        'video_path',
        'bunny_video_id',
        'duration', // Duration in seconds
        'thumbnail',
        'is_trial',
        'is_published',
        'order',
        'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'is_trial' => 'boolean',
            'is_published' => 'boolean',
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
     * Get the instructor for this lesson
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the tags for this lesson
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'lesson_tag');
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
     * Note: All users can now see all lessons, but cannot play premium ones
     */
    public function scopeAccessibleBy($query, User $user = null)
    {
        // All authenticated users can see all lessons
        // Access control is now handled at the playback level
        return $query;
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

    /**
     * Get the lesson thumbnail URL
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        // If it's already a full URL, return it
        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        // Otherwise, generate URL from storage
        return asset('storage/' . $this->thumbnail);
    }

    /**
     * Get duration in minutes (rounded up from seconds)
     * Used for display purposes in UI
     */
    public function getDurationMinutesAttribute(): int
    {
        if (!$this->duration) {
            return 0;
        }

        // Convert seconds to minutes, round up
        return (int) ceil($this->duration / 60);
    }
}
