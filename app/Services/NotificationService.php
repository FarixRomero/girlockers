<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;

class NotificationService
{
    /**
     * Notify all users about a new course
     */
    public function notifyNewCourse(Course $course): void
    {
        $users = User::where('role', 'student')->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_course',
                'title' => 'Nuevo curso disponible',
                'message' => "Se agregó '{$course->title}' a la plataforma",
                'url' => route('courses.show', $course),
                'course_id' => $course->id,
            ]);
        }
    }

    /**
     * Notify all users about a new lesson
     */
    public function notifyNewLesson(Lesson $lesson): void
    {
        $lesson->load('module.course', 'instructor');

        $users = User::where('role', 'student')->get();

        foreach ($users as $user) {
            // Only notify if user can access this lesson
            if (!$lesson->isAccessibleBy($user)) {
                continue;
            }

            $courseName = $lesson->module->course->title ?? 'curso';

            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_lesson',
                'title' => 'Nueva clase disponible',
                'message' => "Se agregó '{$lesson->title}' al {$courseName}",
                'url' => route('lessons.show', $lesson),
                'lesson_id' => $lesson->id,
                'course_id' => $lesson->module->course->id ?? null,
            ]);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Delete a notification
     */
    public function delete(Notification $notification): void
    {
        $notification->delete();
    }

    /**
     * Get user's recent notifications
     */
    public function getRecentNotifications(User $user, int $limit = 10)
    {
        return $user->notifications()
            ->with(['course', 'lesson'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's unread notifications count
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }
}
