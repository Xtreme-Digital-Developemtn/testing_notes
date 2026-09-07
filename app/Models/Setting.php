<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        $settings = Cache::remember('settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value, string $type = 'text'): void
    {
        static::updateOrCreate(['key' => $key], [
            'value' => $value,
            'type'  => $type,
        ]);

        Cache::forget('settings');
    }

    public static function allAsKeyValue(): array
    {
        return static::pluck('value', 'key')->toArray();
    }
}
