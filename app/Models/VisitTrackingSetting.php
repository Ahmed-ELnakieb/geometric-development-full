<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitTrackingSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set(string $key, $value, string $type = null): void
    {
        if ($type === null) {
            $type = static::detectType($value);
        }

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => static::serializeValue($value, $type),
                'type' => $type
            ]
        );
    }

    protected static function detectType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_array($value)) {
            return 'array';
        }
        return 'string';
    }

    protected static function serializeValue($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'integer':
                return (string) $value;
            case 'array':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    protected static function castValue(string $value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return $value === '1';
            case 'integer':
                return (int) $value;
            case 'array':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }
}
