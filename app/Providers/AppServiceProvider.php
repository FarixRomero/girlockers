<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate: manage-users (admin only)
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Gate: manage-content (admin only)
        Gate::define('manage-content', function (User $user) {
            return $user->isAdmin();
        });

        // Gate: access-lesson (check if user can access a specific lesson)
        Gate::define('access-lesson', function (User $user, Lesson $lesson) {
            return $lesson->isAccessibleBy($user);
        });

        // Gate: delete-comment (admin or comment author)
        Gate::define('delete-comment', function (User $user, Comment $comment) {
            return $user->isAdmin() || $user->id === $comment->user_id;
        });
    }
}
