<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MembershipPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'membership_type',
        'payment_status',
        'transaction_id',
        'order_id',
        'izipay_response',
        'payment_method',
        'card_last_four',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'izipay_response' => 'array',
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->id)) {
                $payment->id = (string) Str::uuid();
            }
            if (empty($payment->order_id)) {
                $payment->order_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(array $izipayResponse): void
    {
        $this->update([
            'payment_status' => 'completed',
            'paid_at' => now(),
            'izipay_response' => $izipayResponse,
            'transaction_id' => $izipayResponse['transactions'][0]['uuid'] ?? null,
            'payment_method' => $izipayResponse['transactions'][0]['transactionDetails']['cardDetails']['effectiveBrand'] ?? null,
            'card_last_four' => substr($izipayResponse['transactions'][0]['transactionDetails']['cardDetails']['pan'] ?? '', -4),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): void
    {
        $this->update(['payment_status' => 'failed']);
    }

    /**
     * Scope to get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to get completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }
}
