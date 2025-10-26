<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'key',
        'value',
        'encrypted',
        'description'
    ];

    protected $casts = [
        'encrypted' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        if ($this->encrypted) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value;
            }
        }
        
        return $value;
    }

    public function setValueAttribute($value)
    {
        if ($this->encrypted) {
            $this->attributes['value'] = Crypt::encryptString($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    public static function get(string $key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            $value = $setting ? $setting->value : $default;
            
            // Handle different value types safely
            if (is_array($value)) {
                return $default;
            }
            
            // If value is JSON string, try to decode it
            if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $default; // Return default for array values
                }
            }
            
            return $value;
        } catch (\Exception $e) {
            \Log::error('ChatSetting::get error', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $default;
        }
    }

    public static function set(string $key, $value, bool $encrypted = false, string $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'encrypted' => $encrypted,
                'description' => $description
            ]
        );
    }

    public static function getWhatsAppConfig(): array
    {
        return [
            'app_id' => static::get('whatsapp_app_id'),
            'phone_number' => static::get('whatsapp_phone_number'),
            'access_token' => static::get('whatsapp_access_token'),
            'webhook_url' => static::get('whatsapp_webhook_url'),
            'webhook_verify_token' => static::get('whatsapp_webhook_verify_token'),
        ];
    }
}
