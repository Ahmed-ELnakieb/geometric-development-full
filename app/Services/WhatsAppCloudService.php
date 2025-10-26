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
    private string $baseUrl;
    private ?string $accessToken;
    private ?string $phoneNumberId;
    private ?string $appId;

    public function __construct()
    {
        $config = ChatSetting::getWhatsAppConfig();
        $this->accessToken = $config['access_token'];
        $this->phoneNumberId = $config['phone_number'];
        $this->appId = $config['app_id'];
        
        // Build base URL from configuration
        $baseUrl = config('whatsapp.base_url', 'https://graph.facebook.com');
        $apiVersion = config('whatsapp.api_version', 'v18.0');
        $this->baseUrl = rtrim($baseUrl, '/') . '/' . ltrim($apiVersion, '/');
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
                    'code' => 'en_US' // Use en_US as in the Meta example
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

        Log::info('Sending WhatsApp template message', [
            'to' => $to,
            'formatted_to' => $this->formatPhoneNumber($to),
            'template' => $template,
            'payload' => $payload
        ]);

        return $this->makeApiCall("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Send a custom template with fallback to hello_world
     */
    public function sendTemplateWithFallback(string $to, string $template, array $params = []): array
    {
        try {
            // Try to send the requested template first
            return $this->sendTemplate($to, $template, $params);
        } catch (WhatsAppApiException $e) {
            // If template doesn't exist, fall back to hello_world
            if (str_contains($e->getMessage(), 'Template name does not exist') || 
                str_contains($e->getMessage(), 'does not exist in the translation')) {
                
                Log::warning('Template not found, falling back to hello_world', [
                    'requested_template' => $template,
                    'error' => $e->getMessage()
                ]);
                
                return $this->sendTemplate($to, 'hello_world', []);
            }
            
            // Re-throw other exceptions
            throw $e;
        }
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
            ->withOptions($this->getHttpOptions())
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
            ->withOptions($this->getHttpOptions())
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
            ->withOptions($this->getHttpOptions())
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
            ->withOptions($this->getHttpOptions())
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
                    ->withOptions($this->getHttpOptions())
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
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Remove + if present at the beginning
        if (str_starts_with($cleaned, '+')) {
            $cleaned = substr($cleaned, 1);
        }
        
        // Log the formatted number for debugging
        Log::info('WhatsApp phone number formatting', [
            'original' => $phoneNumber,
            'formatted' => $cleaned
        ]);
        
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

    /**
     * Get message delivery status
     */
    public function getMessageStatus(string $messageId): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->withOptions($this->getHttpOptions())
            ->get("{$this->baseUrl}/{$messageId}");

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to get message status: ' . $response->body(), $response->status());
        }

        return $response->json();
    }

    /**
     * Send a test message with enhanced debugging
     */
    public function sendTestMessageWithDebug(string $to, string $message): array
    {
        $formattedNumber = $this->formatPhoneNumber($to);
        
        Log::info('Sending WhatsApp test message', [
            'original_number' => $to,
            'formatted_number' => $formattedNumber,
            'message' => $message,
            'phone_number_id' => $this->phoneNumberId
        ]);

        $result = $this->sendMessage($to, $message);
        
        Log::info('WhatsApp test message result', [
            'result' => $result,
            'message_id' => $result['messages'][0]['id'] ?? 'No ID returned'
        ]);

        return $result;
    }

    /**
     * Get available message templates
     */
    public function getMessageTemplates(): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        // Try to get templates from the correct endpoint
        $response = Http::withToken($this->accessToken)
            ->withOptions($this->getHttpOptions())
            ->get("{$this->baseUrl}/{$this->phoneNumberId}/message_templates");

        if (!$response->successful()) {
            // If that fails, try the business account endpoint
            $response = Http::withToken($this->accessToken)
                ->withOptions($this->getHttpOptions())
                ->get("{$this->baseUrl}/{$this->appId}/message_templates");
        }

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to get message templates: ' . $response->body(), $response->status());
        }

        return $response->json();
    }

    /**
     * Get WhatsApp Business Account status and information
     */
    public function getBusinessAccountInfo(): array
    {
        if (!$this->isConfigured()) {
            throw new WhatsAppApiException('WhatsApp API not configured', 500);
        }

        $response = Http::withToken($this->accessToken)
            ->withOptions($this->getHttpOptions())
            ->get("{$this->baseUrl}/{$this->appId}", [
                'fields' => 'name,verification_status,business_verification_status,restriction_info'
            ]);

        if (!$response->successful()) {
            throw new WhatsAppApiException('Failed to get business account info: ' . $response->body(), $response->status());
        }

        return $response->json();
    }

    /**
     * Check if account is in production mode
     */
    public function isProductionMode(): bool
    {
        try {
            $accountInfo = $this->getBusinessAccountInfo();
            
            // Check if business verification is complete
            $businessVerified = isset($accountInfo['business_verification_status']) && 
                               $accountInfo['business_verification_status'] === 'verified';
            
            // Check if phone number verification is complete
            $phoneInfo = $this->getBusinessProfile();
            $phoneVerified = isset($phoneInfo['verified_name']);
            
            return $businessVerified && $phoneVerified;
        } catch (\Exception $e) {
            Log::warning('Could not determine WhatsApp production mode status', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get HTTP options for cURL requests
     */
    private function getHttpOptions(): array
    {
        $options = [
            'timeout' => config('whatsapp.timeout', 30),
            'connect_timeout' => config('whatsapp.connect_timeout', 10),
        ];

        // Check if SSL verification is explicitly disabled via environment
        $sslVerify = config('whatsapp.ssl_verify');
        
        if ($sslVerify === false || (is_null($sslVerify) && app()->environment(['local', 'development', 'testing']))) {
            $options['verify'] = false; // Disable SSL verification
            
            Log::warning('SSL verification disabled for WhatsApp API calls', [
                'environment' => app()->environment(),
                'explicit_config' => !is_null($sslVerify)
            ]);
        } else {
            // Enable SSL verification
            $options['verify'] = true;
            
            // Check for custom CA bundle path from config
            $customCaBundle = config('whatsapp.ca_bundle');
            if ($customCaBundle && file_exists($customCaBundle)) {
                $options['cafile'] = $customCaBundle;
                Log::info('Using custom CA bundle for WhatsApp API', ['path' => $customCaBundle]);
            } else {
                // Try to find system CA bundle
                $caBundlePaths = [
                    storage_path('app/cacert.pem'), // Custom downloaded bundle
                    '/etc/ssl/certs/ca-certificates.crt', // Debian/Ubuntu
                    '/etc/pki/tls/certs/ca-bundle.crt',   // RHEL/CentOS
                    '/usr/local/share/certs/ca-root-nss.crt', // FreeBSD
                ];

                foreach ($caBundlePaths as $path) {
                    if (file_exists($path)) {
                        $options['cafile'] = $path;
                        Log::info('Using system CA bundle for WhatsApp API', ['path' => $path]);
                        break;
                    }
                }
            }
        }

        return $options;
    }
}