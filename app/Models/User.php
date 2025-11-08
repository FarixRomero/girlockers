<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'has_full_access',
        'access_granted_at',
        'membership_expires_at',
        'membership_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'has_full_access' => 'boolean',
            'access_granted_at' => 'datetime',
            'membership_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is an administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Check if user has full access to premium content
     */
    public function hasFullAccess(): bool
    {
        return $this->has_full_access;
    }

    /**
     * Grant full access to user with membership duration
     */
    public function grantFullAccess(string $membershipType = 'monthly'): void
    {
        $months = $membershipType === 'quarterly' ? 3 : 1;

        $this->update([
            'has_full_access' => true,
            'access_granted_at' => now(),
            'membership_expires_at' => now()->addMonths($months),
            'membership_type' => $membershipType,
        ]);
    }

    /**
     * Extend membership duration
     */
    public function extendMembership(string $membershipType = 'monthly'): void
    {
        $months = $membershipType === 'quarterly' ? 3 : 1;

        // If membership already expired or doesn't exist, start from now
        $baseDate = $this->membership_expires_at && $this->membership_expires_at->isFuture()
            ? $this->membership_expires_at
            : now();

        $this->update([
            'has_full_access' => true,
            'membership_expires_at' => $baseDate->addMonths($months),
            'membership_type' => $membershipType,
        ]);
    }

    /**
     * Revoke full access from user
     */
    public function revokeFullAccess(): void
    {
        $this->update([
            'has_full_access' => false,
            'access_granted_at' => null,
            'membership_expires_at' => null,
            'membership_type' => null,
        ]);
    }

    /**
     * Check if membership is expired
     */
    public function isMembershipExpired(): bool
    {
        if (!$this->membership_expires_at) {
            return false;
        }

        return $this->membership_expires_at->isPast();
    }

    /**
     * Check if membership is expiring soon (within 7 days)
     */
    public function isMembershipExpiringSoon(): bool
    {
        if (!$this->membership_expires_at) {
            return false;
        }

        return $this->membership_expires_at->isFuture() &&
               $this->membership_expires_at->diffInDays(now()) <= 7;
    }

    /**
     * Get days until membership expires
     */
    public function getDaysUntilExpiration(): ?int
    {
        if (!$this->membership_expires_at) {
            return null;
        }

        return max(0, $this->membership_expires_at->diffInDays(now()));
    }

    /**
     * Get user's comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get user's liked lessons
     */
    public function likedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_likes');
    }

    /**
     * Get user's likes (alias for likedLessons)
     */
    public function likes()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_likes')
            ->withPivot('created_at');
    }

    /**
     * Get user's access request
     */
    public function accessRequest(): HasOne
    {
        return $this->hasOne(AccessRequest::class);
    }

    /**
     * Get user's access requests
     */
    public function accessRequests(): HasMany
    {
        return $this->hasMany(AccessRequest::class);
    }

    /**
     * Get user's notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Get user's unread notifications
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->unread()->latest();
    }

    /**
     * Get user's lesson views
     */
    public function lessonViews(): HasMany
    {
        return $this->hasMany(LessonView::class);
    }

    /**
     * Get user's viewed lessons
     */
    public function viewedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_views')
            ->withPivot('viewed_at', 'completed_at', 'progress_percentage')
            ->orderByPivot('viewed_at', 'desc');
    }

    /**
     * Get user's completed lessons
     */
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_views')
            ->wherePivotNotNull('completed_at')
            ->withPivot('viewed_at', 'completed_at', 'progress_percentage')
            ->orderByPivot('completed_at', 'desc');
    }

    /**
     * Get user's favorite courses
     */
    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'course_favorites')
            ->withTimestamps();
    }

    /**
     * Get the user avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // If it's already a full URL, return it
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // Otherwise, generate URL from S3
        return \Storage::disk('s3')->url($this->avatar);
    }
}
