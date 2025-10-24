<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:generate-vapid-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate VAPID keys for push notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating VAPID keys for push notifications...');
        
        try {
            // Generate keys using OpenSSL
            $privateKey = openssl_pkey_new([
                'curve_name' => 'prime256v1',
                'private_key_type' => OPENSSL_KEYTYPE_EC,
            ]);
            
            if (!$privateKey) {
                throw new \Exception('Failed to generate private key');
            }
            
            // Export private key
            openssl_pkey_export($privateKey, $privateKeyPem);
            
            // Get public key
            $publicKeyDetails = openssl_pkey_get_details($privateKey);
            $publicKeyPem = $publicKeyDetails['key'];
            
            // Convert to base64url format for VAPID
            $privateKeyBase64 = $this->pemToBase64Url($privateKeyPem, 'PRIVATE KEY');
            $publicKeyBase64 = $this->pemToBase64Url($publicKeyPem, 'PUBLIC KEY');
            
            $this->info('VAPID keys generated successfully!');
            $this->line('');
            $this->line('Add these to your .env file:');
            $this->line('');
            $this->line('PWA_VAPID_PUBLIC_KEY="' . $publicKeyBase64 . '"');
            $this->line('PWA_VAPID_PRIVATE_KEY="' . $privateKeyBase64 . '"');
            $this->line('');
            $this->warn('Keep the private key secure and never expose it publicly!');
            
        } catch (\Exception $e) {
            $this->error('Failed to generate VAPID keys: ' . $e->getMessage());
            
            // Fallback: provide sample keys for development
            $this->line('');
            $this->warn('Using sample keys for development (replace in production):');
            $this->line('');
            $this->line('PWA_VAPID_PUBLIC_KEY="BEl62iUYgUivxIkv69yViEuiBIa40HI0DLLuxN4AbgPyIdkAGfWKOqJOTKh-JnLKBJqBVkb0VplJBQVgpcUvQoA"');
            $this->line('PWA_VAPID_PRIVATE_KEY="nCScfhigsOpkAHFuINDqEGnyRkmT9E-VcN5ma-5_LyM"');
            
            return 1;
        }
        
        return 0;
    }
    
    private function pemToBase64Url($pem, $type)
    {
        // Remove PEM headers and footers
        $pem = str_replace("-----BEGIN {$type}-----", '', $pem);
        $pem = str_replace("-----END {$type}-----", '', $pem);
        $pem = str_replace(["\r", "\n", " "], '', $pem);
        
        // Convert to base64url
        $binary = base64_decode($pem);
        return rtrim(strtr(base64_encode($binary), '+/', '-_'), '=');
    }
}
