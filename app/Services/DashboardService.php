<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Tag;
use App\Models\User;

class DashboardService
{
    /**
     * Get user statistics in a single optimized query
     *
     * @param User $user
     * @return array
     */
    public function getUserStats(User $user): array
    {
        // Get completed lessons count and total minutes in a single query
        $likesData = $user->likes()
            ->selectRaw('COUNT(*) as completed_lessons, SUM(duration) as total_minutes')
            ->first();

        return [
            'name' => $user->name,
            'total_minutes' => (int) ($likesData->total_minutes ?? 0),
            'completed_lessons' => (int) ($likesData->completed_lessons ?? 0),
            'has_access' => $user->hasFullAccess(),
        ];
    }

    /**
     * Get recent lessons with eager loading
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentLessons(User $user, int $limit = 8)
    {
        return Lesson::accessibleBy($user)
            ->with(['module.course', 'instructor', 'tags'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get lessons by tag name
     *
     * @param User $user
     * @param string $tagName
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLessonsByTag(User $user, string $tagName, int $limit = 8)
    {
        $tag = Tag::where('name', $tagName)->first();

        if (!$tag) {
            return collect();
        }

        return Lesson::whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->accessibleBy($user)
            ->with(['module.course', 'instructor', 'tags'])
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    /**
     * Get user's saved/liked lessons with eager loading
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSavedLessons(User $user, int $limit = 8)
    {
        return $user->likedLessons()
            ->with(['module.course', 'instructor', 'tags'])
            ->take($limit)
            ->get();
    }

    /**
     * Get trending courses (ordered by modules count)
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTrendingCourses(int $limit = 6)
    {
        return Course::where('is_published', true)
            ->withCount('modules')
            ->with('modules')
            ->orderByDesc('modules_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get all dashboard data in optimized queries
     *
     * @param User $user
     * @return array
     */
    public function getDashboardData(User $user): array
    {
        return [
            'stats' => $this->getUserStats($user),
            'recentLessons' => $this->getRecentLessons($user),
            'coreografiaLessons' => $this->getLessonsByTag($user, 'Coreografía'),
            'savedLessons' => $this->getSavedLessons($user),
            'trendingCourses' => $this->getTrendingCourses(),
        ];
    }
}
