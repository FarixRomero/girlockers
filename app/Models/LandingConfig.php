<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get config value by key with caching
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("landing_config_{$key}", 3600, function () use ($key, $default) {
            $config = self::where('key', $key)->first();

            if (!$config) {
                return $default;
            }

            // Decode JSON if type is json
            if ($config->type === 'json' && is_string($config->value)) {
                return json_decode($config->value, true);
            }

            return $config->value;
        });
    }

    /**
     * Set config value by key
     */
    public static function setValue(string $key, $value): void
    {
        $config = self::where('key', $key)->first();

        if (!$config) {
            return;
        }

        // Encode to JSON if type is json
        if ($config->type === 'json' && is_array($value)) {
            $value = json_encode($value);
        }

        $config->update(['value' => $value]);

        // Clear cache
        Cache::forget("landing_config_{$key}");
    }

    /**
     * Get all configs by group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Clear all landing config cache
     */
    public static function clearCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("landing_config_{$key}");
        }
    }
}
