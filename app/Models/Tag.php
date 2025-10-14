<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the lessons that have this tag
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_tag');
    }
}
