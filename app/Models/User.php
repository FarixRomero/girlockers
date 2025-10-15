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
        'role',
        'has_full_access',
        'access_granted_at',
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
     * Grant full access to user
     */
    public function grantFullAccess(): void
    {
        $this->update([
            'has_full_access' => true,
            'access_granted_at' => now(),
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
        ]);
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
}
