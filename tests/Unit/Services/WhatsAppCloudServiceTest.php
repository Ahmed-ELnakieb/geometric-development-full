<?php

namespace Tests\Unit\Services;

use App\Services\WhatsAppCloudService;
use App\Exceptions\WhatsAppApiException;
use App\Models\ChatSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class WhatsAppCloudServiceTest extends TestCase
{
    use RefreshDatabase;

    private WhatsAppCloudService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        ChatSetting::set('whatsapp_access_token', 'test_token');
        ChatSetting::set('whatsapp_phone_number', '1234567890');
        ChatSetting::set('whatsapp_app_id', 'test_app_id');
        ChatSetting::set('whatsapp_app_secret', 'test_secret');
        
        $this->service = new WhatsAppCloudService();
    }

    public function test_send_message_success(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [
                    [
                        'input' => '+1234567890',
                        'wa_id' => '1234567890'
                    ]
                ],
                'messages' => [
                    [
                        'id' => 'wamid.test123'
                    ]
                ]
            ], 200)
        ]);

        $result = $this->service->sendMessage('+1234567890', 'Test message');

        $this->assertArrayHasKey('messages', $result);
        $this->assertEquals('wamid.test123', $result['messages'][0]['id']);

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://graph.facebook.com/v18.0/1234567890/messages' &&
                   $request['messaging_product'] === 'whatsapp' &&
                   $request['text']['body'] === 'Test message';
        });
    }

    public function test_send_message_with_invalid_config(): void
    {
        ChatSetting::set('whatsapp_access_token', '');
        $service = new WhatsAppCloudService();

        $this->expectException(WhatsAppApiException::class);
        $this->expectExceptionMessage('WhatsApp API not configured');

        $service->sendMessage('+1234567890', 'Test message');
    }

    public function test_send_template_message(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [
                    [
                        'input' => '+1234567890',
                        'wa_id' => '1234567890'
                    ]
                ],
                'messages' => [
                    [
                        'id' => 'wamid.template123'
                    ]
                ]
            ], 200)
        ]);

        $result = $this->service->sendTemplate('+1234567890', 'hello_world', ['John']);

        $this->assertArrayHasKey('messages', $result);
        $this->assertEquals('wamid.template123', $result['messages'][0]['id']);

        Http::assertSent(function (Request $request) {
            return $request['type'] === 'template' &&
                   $request['template']['name'] === 'hello_world' &&
                   isset($request['template']['components']);
        });
    }

    public function test_upload_media(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'id' => 'media123'
            ], 200)
        ]);

        $file = UploadedFile::fake()->image('test.jpg');
        $mediaId = $this->service->uploadMedia($file);

        $this->assertEquals('media123', $mediaId);

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), '/media') &&
                   $request->hasFile('file');
        });
    }

    public function test_get_business_profile(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'display_phone_number' => '+1 234-567-8900',
                'verified_name' => 'Test Business',
                'quality_rating' => 'GREEN'
            ], 200)
        ]);

        $profile = $this->service->getBusinessProfile();

        $this->assertEquals('Test Business', $profile['verified_name']);
        $this->assertEquals('GREEN', $profile['quality_rating']);

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), '1234567890') &&
                   str_contains($request->url(), 'fields=');
        });
    }

    public function test_validate_webhook_signature_valid(): void
    {
        $payload = '{"test": "data"}';
        $secret = 'test_secret';
        $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        $isValid = $this->service->validateWebhookSignature($payload, $signature);

        $this->assertTrue($isValid);
    }

    public function test_validate_webhook_signature_invalid(): void
    {
        $payload = '{"test": "data"}';
        $signature = 'sha256=invalid_signature';

        $isValid = $this->service->validateWebhookSignature($payload, $signature);

        $this->assertFalse($isValid);
    }

    public function test_mark_message_as_read(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'success' => true
            ], 200)
        ]);

        $result = $this->service->markMessageAsRead('wamid.test123');

        $this->assertTrue($result['success']);

        Http::assertSent(function (Request $request) {
            return $request['messaging_product'] === 'whatsapp' &&
                   $request['status'] === 'read' &&
                   $request['message_id'] === 'wamid.test123';
        });
    }

    public function test_get_media_url(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'url' => 'https://example.com/media/test.jpg',
                'mime_type' => 'image/jpeg',
                'sha256' => 'abc123',
                'file_size' => 12345
            ], 200)
        ]);

        $url = $this->service->getMediaUrl('media123');

        $this->assertEquals('https://example.com/media/test.jpg', $url);

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), 'media123');
        });
    }

    public function test_download_media(): void
    {
        Http::fake([
            'example.com/*' => Http::response('binary_image_data', 200)
        ]);

        $content = $this->service->downloadMedia('https://example.com/media/test.jpg');

        $this->assertEquals('binary_image_data', $content);
    }

    public function test_api_error_handling(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid access token',
                    'type' => 'OAuthException',
                    'code' => 190
                ]
            ], 401)
        ]);

        $this->expectException(WhatsAppApiException::class);
        $this->expectExceptionMessage('Invalid access token');

        $this->service->sendMessage('+1234567890', 'Test message');
    }

    public function test_rate_limiting_retry(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::sequence()
                ->push(['error' => ['message' => 'Rate limited']], 429, ['Retry-After' => '1'])
                ->push([
                    'messaging_product' => 'whatsapp',
                    'messages' => [['id' => 'wamid.success']]
                ], 200)
        ]);

        $result = $this->service->sendMessage('+1234567890', 'Test message');

        $this->assertEquals('wamid.success', $result['messages'][0]['id']);
    }

    public function test_phone_number_formatting(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messaging_product' => 'whatsapp',
                'messages' => [['id' => 'wamid.test']]
            ], 200)
        ]);

        // Test various phone number formats
        $this->service->sendMessage('(555) 123-4567', 'Test');
        $this->service->sendMessage('555-123-4567', 'Test');
        $this->service->sendMessage('5551234567', 'Test');

        Http::assertSentCount(3);

        // All should be formatted to include country code
        Http::assertSent(function (Request $request) {
            return $request['to'] === '15551234567';
        });
    }

    public function test_test_connection_success(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'display_phone_number' => '+1 234-567-8900',
                'verified_name' => 'Test Business'
            ], 200)
        ]);

        $result = $this->service->testConnection();

        $this->assertArrayHasKey('verified_name', $result);
        $this->assertEquals('Test Business', $result['verified_name']);
    }

    public function test_test_connection_failure(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'error' => ['message' => 'Invalid token']
            ], 401)
        ]);

        $result = $this->service->testConnection();

        $this->assertArrayHasKey('success', $result);
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_get_webhook_verify_token(): void
    {
        ChatSetting::set('whatsapp_webhook_verify_token', 'verify123');
        
        $token = $this->service->getWebhookVerifyToken();
        
        $this->assertEquals('verify123', $token);
    }
}
