<?php

namespace App\Services;

use App\Models\ChatSetting;
use App\Exceptions\WhatsAppApiException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class WhatsAppCloudService
{
    private string $baseUrl = 'https://graph.facebook.com/v18.0';
    private ?string $accessToken;
    private ?string $phoneNumberId;
    private ?string $appId;

    public function __construct()
    {
        $config = ChatSetting::getWhatsAppConfig();
        $this->accessToken = $config['access_token'];
        $this->phoneNumberId = $config['phone_number'];
        $this->appId = $config['app_id'];
    }

    /**
     * Send a text message to WhatsApp
     */
    public function sendMessage(string $to, string $message): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ];

        return $this->makeApiCall("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Send a template message to WhatsApp
     */
    public function sendTemplate(string $to, string $template, array $params = []): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'template',
            'template' => [
                'name' => $template,
                'language' => [
                    'code' => 'en'
                ]
            ]
        ];

        if (!empty($params)) {
            $payload['template']['components'] = [
                [
                    'type' => 'body',
                    'parameters' => array_map(fn($param) => ['type' => 'text', 'text' => $param], $params)
                ]
            ];
        }

        return $this->makeApiCall("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Upload media to WhatsApp
     */
    public function uploadMedia(UploadedFile $file): string
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->attach('file', $file->getContent(), $file->getClientOriginalName())
            ->post("{$this->baseUrl}/{$this->appId}/media", [
                'messaging_product' => 'whatsapp'
            ]);

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to upload media: ' . $response->body(), $response->status());
        }

        $data = $response->json();
        return $data['id'] ?? '';
    }

    /**
     * Get business profile information
     */
    public function getBusinessProfile(): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/{$this->phoneNumberId}", [
                'fields' => 'display_phone_number,verified_name,quality_rating'
            ]);

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to get business profile: ' . $response->body(), $response->status());
        }

        return $response->json();
    }

    /**
     * Validate webhook signature from Meta
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $appSecret = ChatSetting::get('whatsapp_app_secret');
        
        if (!$appSecret) {
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Mark message as read
     */
    public function markMessageAsRead(string $messageId): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId
        ];

        return $this->makeApiCall("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Get media URL by media ID
     */
    public function getMediaUrl(string $mediaId): string
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/{$mediaId}");

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to get media URL: ' . $response->body(), $response->status());
        }

        $data = $response->json();
        return $data['url'] ?? '';
    }

    /**
     * Download media content
     */
    public function downloadMedia(string $mediaUrl): string
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->get($mediaUrl);

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to download media: ' . $response->body(), $response->status());
        }

        return $response->body();
    }

    /**
     * Make API call with error handling and retry logic
     */
    private function makeApiCall(string $endpoint, array $payload, int $retries = 3): array
    {
        $attempt = 0;
        
        while ($attempt < $retries) {
            try {
                $response = Http::withToken($this->accessToken)
                    ->post($this->baseUrl . $endpoint, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    Log::info('WhatsApp API call successful', [
                        'endpoint' => $endpoint,
                        'response' => $data
                    ]);
                    
                    return $data;
                }

                // Handle rate limiting
                if ($response->status() === 429) {
                    $retryAfter = $response->header('Retry-After', 60);
                    sleep((int) $retryAfter);
                    $attempt++;
                    continue;
                }

                // Handle other errors
                $error = $response->json();
                throw new WhatsAppApiException(
                    $error['error']['message'] ?? 'API call failed',
                    $response->status(),
                    $error
                );

            } catch (WhatsAppApiException $e) {
                if ($attempt === $retries - 1) {
                    Log::error('WhatsApp API call failed', [
                        'endpoint' => $endpoint,
                        'payload' => $payload,
                        'error' => $e->getMessage(),
                        'context' => $e->getContext()
                    ]);
                    throw $e;
                }
                
                $attempt++;
                sleep(pow(2, $attempt)); // Exponential backoff
            }
        }

        throw new WhatsAppApiException('Max retries exceeded', 500);
    }

    /**
     * Format phone number for WhatsApp API
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present
        if (!str_starts_with($cleaned, '1') && strlen($cleaned) === 10) {
            $cleaned = '1' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Check if the service is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->phoneNumberId);
    }

    /**
     * Test the API connection
     */
    public function testConnection(): array
    {
        try {
            return $this->getBusinessProfile();
        } catch (WhatsAppApiException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get webhook verification token
     */
    public function getWebhookVerifyToken(): ?string
    {
        return ChatSetting::get('whatsapp_webhook_verify_token');
    }
}