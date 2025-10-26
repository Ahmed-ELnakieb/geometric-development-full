<?php

namespace Tests\Unit\Models;

use App\Models\ChatSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_setting_has_fillable_attributes(): void
    {
        $fillable = [
            'key',
            'value',
            'encrypted',
            'description'
        ];

        $setting = new ChatSetting();
        $this->assertEquals($fillable, $setting->getFillable());
    }

    public function test_chat_setting_casts_encrypted_to_boolean(): void
    {
        $setting = ChatSetting::create([
            'key' => 'test_key',
            'value' => 'test_value',
            'encrypted' => 1
        ]);

        $this->assertIsBool($setting->encrypted);
        $this->assertTrue($setting->encrypted);
    }

    public function test_encrypted_value_is_encrypted_when_stored(): void
    {
        $setting = new ChatSetting([
            'key' => 'test_key',
            'encrypted' => true
        ]);
        
        $setting->value = 'secret_value';
        $setting->save();

        // The raw value should be encrypted
        $this->assertNotEquals('secret_value', $setting->getRawOriginal('value'));
        // But the accessor should return the decrypted value
        $this->assertEquals('secret_value', $setting->value);
    }

    public function test_non_encrypted_value_is_stored_as_plain_text(): void
    {
        $setting = ChatSetting::create([
            'key' => 'test_key',
            'value' => 'plain_value',
            'encrypted' => false
        ]);

        $this->assertEquals('plain_value', $setting->getRawOriginal('value'));
        $this->assertEquals('plain_value', $setting->value);
    }

    public function test_get_method_returns_setting_value(): void
    {
        ChatSetting::create([
            'key' => 'test_setting',
            'value' => 'test_value'
        ]);

        $value = ChatSetting::get('test_setting');

        $this->assertEquals('test_value', $value);
    }

    public function test_get_method_returns_default_when_setting_not_found(): void
    {
        $value = ChatSetting::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    public function test_set_method_creates_new_setting(): void
    {
        ChatSetting::set('new_key', 'new_value', false, 'Test description');

        $setting = ChatSetting::where('key', 'new_key')->first();

        $this->assertNotNull($setting);
        $this->assertEquals('new_value', $setting->value);
        $this->assertFalse($setting->encrypted);
        $this->assertEquals('Test description', $setting->description);
    }

    public function test_set_method_updates_existing_setting(): void
    {
        ChatSetting::create([
            'key' => 'existing_key',
            'value' => 'old_value'
        ]);

        ChatSetting::set('existing_key', 'new_value');

        $setting = ChatSetting::where('key', 'existing_key')->first();

        $this->assertEquals('new_value', $setting->value);
    }

    public function test_get_whatsapp_config_returns_configuration_array(): void
    {
        ChatSetting::set('whatsapp_app_id', 'test_app_id');
        ChatSetting::set('whatsapp_phone_number', '+1234567890');
        ChatSetting::set('whatsapp_access_token', 'test_token');
        ChatSetting::set('whatsapp_webhook_url', 'https://example.com/webhook');
        ChatSetting::set('whatsapp_webhook_verify_token', 'verify_token');

        $config = ChatSetting::getWhatsAppConfig();

        $this->assertIsArray($config);
        $this->assertEquals('test_app_id', $config['app_id']);
        $this->assertEquals('+1234567890', $config['phone_number']);
        $this->assertEquals('test_token', $config['access_token']);
        $this->assertEquals('https://example.com/webhook', $config['webhook_url']);
        $this->assertEquals('verify_token', $config['webhook_verify_token']);
    }

    public function test_get_whatsapp_config_returns_null_values_for_missing_settings(): void
    {
        $config = ChatSetting::getWhatsAppConfig();

        $this->assertIsArray($config);
        $this->assertNull($config['app_id']);
        $this->assertNull($config['phone_number']);
        $this->assertNull($config['access_token']);
        $this->assertNull($config['webhook_url']);
        $this->assertNull($config['webhook_verify_token']);
    }
}
