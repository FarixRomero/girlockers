<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LessonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can browse lessons
    }

    /**
     * Determine whether the user can view the model.
     * All authenticated users can now see all lessons
     * (but playback is restricted to accessible lessons)
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // All authenticated users can view any lesson
        return true;
    }

    /**
     * Determine whether the user can comment on the lesson.
     */
    public function comment(User $user, Lesson $lesson): bool
    {
        // Users can only comment on lessons they can access
        return $lesson->isAccessibleBy($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }
}
