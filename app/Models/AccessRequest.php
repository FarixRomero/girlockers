<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'request_type',
        'membership_type',
        'country_code',
        'phone_number',
    ];

    /**
     * Get the user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Approve the access request
     */
    public function approve(): void
    {
        $this->update(['status' => 'approved']);

        $membershipType = $this->membership_type ?? 'monthly';

        if ($this->request_type === 'renewal') {
            $this->user->extendMembership($membershipType);
        } else {
            $this->user->grantFullAccess($membershipType);
        }
    }

    /**
     * Reject the access request
     */
    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if request is for renewal
     */
    public function isRenewal(): bool
    {
        return $this->request_type === 'renewal';
    }

    /**
     * Check if request is for new access
     */
    public function isNew(): bool
    {
        return $this->request_type === 'new';
    }

    /**
     * Scope to get pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get renewal requests
     */
    public function scopeRenewal($query)
    {
        return $query->where('request_type', 'renewal');
    }
}
