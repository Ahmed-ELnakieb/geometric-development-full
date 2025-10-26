<?php

namespace Tests\Feature;

use App\Models\ChatSetting;
use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\WebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        ChatSetting::set('whatsapp_access_token', 'test_token');
        ChatSetting::set('whatsapp_phone_number', '1234567890');
        ChatSetting::set('whatsapp_app_id', 'test_app_id');
        ChatSetting::set('whatsapp_app_secret', 'test_secret');
        ChatSetting::set('whatsapp_webhook_verify_token', 'verify_token_123');
    }

    public function test_webhook_verification_success(): void
    {
        $response = $this->get('/api/whatsapp/webhook?' . http_build_query([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'verify_token_123',
            'hub_challenge' => 'challenge_string'
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('challenge_string');
    }

    public function test_webhook_verification_invalid_token(): void
    {
        $response = $this->get('/api/whatsapp/webhook?' . http_build_query([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'invalid_token',
            'hub_challenge' => 'challenge_string'
        ]));

        $response->assertStatus(403);
    }

    public function test_webhook_handles_incoming_text_message(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['success' => true], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '1234567890',
                                    'phone_number_id' => '1234567890'
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '5551234567'
                                    ]
                                ],
                                'messages' => [
                                    [
                                        'from' => '5551234567',
                                        'id' => 'wamid.test123',
                                        'timestamp' => '1234567890',
                                        'text' => [
                                            'body' => 'Hello, I need help!'
                                        ],
                                        'type' => 'text'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(200);

        // Check that conversation was created
        $this->assertDatabaseHas('conversations', [
            'visitor_phone_number' => '5551234567',
            'status' => 'active'
        ]);

        // Check that message was created
        $this->assertDatabaseHas('chat_messages', [
            'whatsapp_message_id' => 'wamid.test123',
            'direction' => 'inbound',
            'content' => 'Hello, I need help!',
            'message_type' => 'text'
        ]);

        // Check that webhook event was logged
        $this->assertDatabaseHas('webhook_events', [
            'event_type' => 'message',
            'processed' => true
        ]);
    }

    public function test_webhook_handles_message_status_update(): void
    {
        // Create existing message
        $conversation = Conversation::factory()->create();
        $message = ChatMessage::factory()->create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'wamid.test123',
            'status' => 'sent'
        ]);

        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '1234567890',
                                    'phone_number_id' => '1234567890'
                                ],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.test123',
                                        'status' => 'delivered',
                                        'timestamp' => '1234567890',
                                        'recipient_id' => '5551234567'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(200);

        // Check that message status was updated
        $message->refresh();
        $this->assertEquals('delivered', $message->status);
    }

    public function test_webhook_handles_image_message(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'url' => 'https://example.com/image.jpg'
            ], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '1234567890',
                                    'phone_number_id' => '1234567890'
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '5551234567'
                                    ]
                                ],
                                'messages' => [
                                    [
                                        'from' => '5551234567',
                                        'id' => 'wamid.image123',
                                        'timestamp' => '1234567890',
                                        'image' => [
                                            'caption' => 'Check this out!',
                                            'mime_type' => 'image/jpeg',
                                            'sha256' => 'abc123',
                                            'id' => 'media123'
                                        ],
                                        'type' => 'image'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(200);

        // Check that message was created with correct type and media URL
        $this->assertDatabaseHas('chat_messages', [
            'whatsapp_message_id' => 'wamid.image123',
            'message_type' => 'image',
            'content' => 'Check this out!',
            'media_url' => 'https://example.com/image.jpg'
        ]);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => []
                ]
            ]
        ];

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => 'sha256=invalid_signature'
        ]);

        $response->assertStatus(401);
    }

    public function test_webhook_handles_duplicate_messages(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['success' => true], 200)
        ]);

        // Create existing message
        $conversation = Conversation::factory()->create();
        ChatMessage::factory()->create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'wamid.duplicate123'
        ]);

        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '1234567890',
                                    'phone_number_id' => '1234567890'
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '5551234567'
                                    ]
                                ],
                                'messages' => [
                                    [
                                        'from' => '5551234567',
                                        'id' => 'wamid.duplicate123',
                                        'timestamp' => '1234567890',
                                        'text' => [
                                            'body' => 'Duplicate message'
                                        ],
                                        'type' => 'text'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(200);

        // Should still only have one message with this ID
        $this->assertEquals(1, ChatMessage::where('whatsapp_message_id', 'wamid.duplicate123')->count());
    }

    public function test_webhook_rate_limiting(): void
    {
        $payload = ['entry' => []];
        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        // Make many requests quickly
        for ($i = 0; $i < 105; $i++) {
            $response = $this->postJson('/api/whatsapp/webhook', $payload, [
                'X-Hub-Signature-256' => $signature
            ]);
            
            if ($i < 100) {
                $this->assertNotEquals(429, $response->getStatusCode());
            }
        }

        // The 101st request should be rate limited
        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);
        
        $response->assertStatus(429);
    }

    public function test_webhook_handles_malformed_payload(): void
    {
        $malformedJson = 'invalid json';
        $signature = 'sha256=' . hash_hmac('sha256', $malformedJson, 'test_secret');

        $response = $this->call('POST', '/api/whatsapp/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_HUB_SIGNATURE_256' => $signature,
        ], $malformedJson);

        $response->assertStatus(500);
    }

    public function test_webhook_creates_visitor_session(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['success' => true], 200)
        ]);

        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '1234567890',
                                    'phone_number_id' => '1234567890'
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '5551234567'
                                    ]
                                ],
                                'messages' => [
                                    [
                                        'from' => '5551234567',
                                        'id' => 'wamid.session123',
                                        'timestamp' => '1234567890',
                                        'text' => [
                                            'body' => 'Hello!'
                                        ],
                                        'type' => 'text'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(200);

        // Check that visitor session was created
        $this->assertDatabaseHas('visitor_sessions', [
            'utm_source' => 'whatsapp',
            'utm_medium' => 'messaging'
        ]);
    }

    public function test_webhook_logs_failed_processing(): void
    {
        // Create invalid payload that will cause processing to fail
        $payload = [
            'entry' => [
                [
                    'id' => 'entry_id',
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        // Missing required fields to cause failure
                                        'id' => 'wamid.invalid'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];

        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), 'test_secret');

        $response = $this->postJson('/api/whatsapp/webhook', $payload, [
            'X-Hub-Signature-256' => $signature
        ]);

        $response->assertStatus(500);

        // Check that webhook event was logged as failed
        $this->assertDatabaseHas('webhook_events', [
            'event_type' => 'message',
            'processed' => false
        ]);
    }
}
