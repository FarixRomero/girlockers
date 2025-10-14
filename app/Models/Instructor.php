<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instagram',
        'avatar',
        'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'likes_count' => 'integer',
        ];
    }

    /**
     * Get the courses for this instructor
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the lessons for this instructor
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the count of videos/lessons for this instructor
     */
    public function videosCount(): int
    {
        return $this->lessons()->count();
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
