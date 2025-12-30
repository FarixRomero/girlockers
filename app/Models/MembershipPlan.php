<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'price',
        'currency',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the price for a specific membership type
     */
    public static function getPrice(string $type): float
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->value('price') ?? 0;
    }
}
