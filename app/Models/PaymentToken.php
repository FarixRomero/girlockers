<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PaymentToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'payment_method_token',
        'card_brand',
        'card_last_four',
        'card_expiry_month',
        'card_expiry_year',
        'is_default',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($token) {
            if (empty($token->id)) {
                $token->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user that owns the token
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get masked card number
     */
    public function getMaskedCardAttribute(): string
    {
        if (!$this->card_last_four) {
            return '•••• •••• •••• ••••';
        }
        return '•••• •••• •••• ' . $this->card_last_four;
    }

    /**
     * Get formatted card brand name
     */
    public function getCardBrandNameAttribute(): string
    {
        return match($this->card_brand) {
            'VISA' => 'Visa',
            'MASTERCARD' => 'Mastercard',
            'AMEX' => 'American Express',
            default => ucfirst(strtolower($this->card_brand ?? 'Tarjeta')),
        };
    }

    /**
     * Check if card is expired
     */
    public function isExpired(): bool
    {
        if (!$this->card_expiry_month || !$this->card_expiry_year) {
            return false;
        }
        $expiryDate = Carbon::createFromFormat('Y-m', $this->card_expiry_year . '-' . $this->card_expiry_month)
            ->endOfMonth();
        return $expiryDate->isPast();
    }

    /**
     * Set this token as default for the user
     */
    public function setAsDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    /**
     * Scope to get active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
