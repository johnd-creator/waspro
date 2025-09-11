<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "app_setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $description = ''): void
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
        
        // Clear cache
        Cache::forget("app_setting_{$key}");
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, string $type)
    {
        return match($type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true),
            'json' => json_decode($value),
            default => $value,
        };
    }

    /**
     * Get limbah expiry days setting
     */
    public static function getLimbahExpiryDays(): int
    {
        return static::get('limbah_expiry_days', 90);
    }

    /**
     * Set limbah expiry days setting
     */
    public static function setLimbahExpiryDays(int $days): void
    {
        static::set('limbah_expiry_days', $days, 'integer', 'Jumlah hari maksimal penyimpanan limbah sebelum kadaluarsa');
    }
}
