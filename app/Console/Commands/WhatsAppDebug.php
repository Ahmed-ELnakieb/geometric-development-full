<?php

namespace App\Console\Commands;

use App\Services\WhatsAppCloudService;
use Illuminate\Console\Command;

class WhatsAppDebug extends Command
{
    protected $signature = 'whatsapp:debug {phone} {message?} {--template=hello_world : Send template message instead of text}';
    
    protected $description = 'Debug WhatsApp message sending with detailed logging';

    public function handle(): int
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message') ?? 'Test message from WhatsApp debug command';
        $templateName = $this->option('template');
        $useTemplate = $templateName !== null;
        
        $this->info("🔍 Debugging WhatsApp message to: {$phone}");
        if ($useTemplate) {
            $this->info("📋 Template: {$templateName}");
            $this->info("✅ Template messages work in both test and production mode!");
        } else {
            $this->info("📝 Message: {$message}");
            $this->warn("⚠️  Text messages only work in production mode");
        }
        $this->newLine();
        
        try {
            $whatsappService = app(WhatsAppCloudService::class);
            
            // Check configuration
            if (!$whatsappService->isConfigured()) {
                $this->error('❌ WhatsApp service is not configured properly');
                return self::FAILURE;
            }
            
            $this->info('✅ WhatsApp service is configured');
            
            // Test connection
            $this->info('🔗 Testing connection...');
            $connectionResult = $whatsappService->testConnection();
            
            if (isset($connectionResult['verified_name'])) {
                $this->info("✅ Connected to: {$connectionResult['verified_name']}");
                
                // Check account mode
                $this->info('🔍 Checking account mode...');
                try {
                    $isProduction = $whatsappService->isProductionMode();
                    if ($isProduction) {
                        $this->info('🟢 Account is in PRODUCTION mode - messages will be delivered');
                    } else {
                        $this->warn('🟡 Account is in TEST mode - messages won\'t reach actual WhatsApp');
                        $this->warn('   Only test numbers in Meta Business Manager will work');
                        $this->warn('   To go live, complete business verification in Meta Business Manager');
                    }
                } catch (\Exception $e) {
                    $this->warn('⚠️  Could not determine account mode: ' . $e->getMessage());
                }
            } else {
                $this->error('❌ Connection failed: ' . ($connectionResult['error'] ?? 'Unknown error'));
                return self::FAILURE;
            }
            
            // List available templates if using template
            if ($useTemplate) {
                $this->info('📋 Checking available templates...');
                try {
                    $templates = $whatsappService->getMessageTemplates();
                    if (isset($templates['data']) && is_array($templates['data'])) {
                        $this->info('Available templates:');
                        foreach ($templates['data'] as $template) {
                            $status = $template['status'] ?? 'UNKNOWN';
                            $this->line("  • {$template['name']} ({$status})");
                        }
                    } else {
                        $this->warn('No templates found or could not retrieve templates');
                    }
                } catch (\Exception $e) {
                    $this->warn('Could not retrieve templates: ' . $e->getMessage());
                }
                $this->newLine();
            }
            
            // Send test message
            if ($useTemplate) {
                $this->info("📤 Sending template message: {$templateName}...");
                $result = $whatsappService->sendTemplateWithFallback($phone, $templateName);
            } else {
                $this->info('📤 Sending text message...');
                $result = $whatsappService->sendTestMessageWithDebug($phone, $message);
            }
            
            if (isset($result['messages'][0]['id'])) {
                $messageId = $result['messages'][0]['id'];
                $this->info("✅ Message sent successfully!");
                $this->info("📋 Message ID: {$messageId}");
                
                // Try to get message status
                $this->info('📊 Checking message status...');
                try {
                    $status = $whatsappService->getMessageStatus($messageId);
                    $this->info('📈 Message status: ' . json_encode($status, JSON_PRETTY_PRINT));
                } catch (\Exception $e) {
                    $this->warn('⚠️  Could not retrieve message status: ' . $e->getMessage());
                }
                
            } else {
                $this->error('❌ Failed to send message');
                $this->error('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
                return self::FAILURE;
            }
            
            $this->newLine();
            $this->info('🎯 Debugging Tips:');
            if ($useTemplate) {
                $this->info('✅ Template messages should be delivered to WhatsApp even in test mode!');
                $this->line('• Check your WhatsApp for the template message');
                $this->line('• Template messages work with any WhatsApp number');
                $this->line('• No conversation window required for templates');
            } else {
                $this->line('• Text messages only work in production mode');
                $this->line('• Check if the phone number has messaged your business first');
                $this->line('• Try using --template=hello_world for real delivery');
            }
            $this->line('• Ensure the number includes the correct country code');
            $this->line('• Verify your WhatsApp Business Account is approved');
            $this->line('• Check Meta Business Manager for any restrictions');
            $this->line('• Review the application logs for detailed information');
            
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}