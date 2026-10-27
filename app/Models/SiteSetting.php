<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        $val = $setting->value;
        // Attempt json decode
        $decoded = json_decode($val, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return $val;
    }

    public static function set(string $key, $value): self
    {
        $encoded = is_array($value) || is_bool($value) ? json_encode($value) : (string) $value;
        return static::updateOrCreate(['key' => $key], ['value' => $encoded]);
    }
}
