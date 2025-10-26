<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadCaCertificates extends Command
{
    protected $signature = 'whatsapp:download-ca-bundle {--force : Force download even if file exists}';

    protected $description = 'Download CA certificate bundle for WhatsApp API SSL connections';

    public function handle(): int
    {
        $this->info('Downloading CA certificate bundle for WhatsApp API...');

        $filePath = storage_path('app/cacert.pem');

        // Check if file already exists
        if (file_exists($filePath) && ! $this->option('force')) {
            $this->warn('CA certificate bundle already exists at: '.$filePath);

            if (! $this->confirm('Do you want to download a fresh copy?')) {
                $this->info('Skipping download.');

                return self::SUCCESS;
            }
        }

        try {
            $this->info('Downloading from: https://curl.se/ca/cacert.pem');

            // Download the CA bundle
            $response = Http::timeout(60)->get('https://curl.se/ca/cacert.pem');

            if (! $response->successful()) {
                $this->error('Failed to download CA certificate bundle. HTTP Status: '.$response->status());

                return self::FAILURE;
            }

            // Save the file
            file_put_contents($filePath, $response->body());

            $this->info('✅ CA certificate bundle downloaded successfully!');
            $this->line('📁 Saved to: '.$filePath);
            $this->line('📊 File size: '.$this->formatBytes(filesize($filePath)));

            // Verify the file
            if ($this->verifyCaBundle($filePath)) {
                $this->info('✅ CA bundle verification passed');
            } else {
                $this->warn('⚠️  CA bundle verification failed - file may be corrupted');
            }

            $this->newLine();
            $this->info('The WhatsApp API service will now use this CA bundle for SSL verification.');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to download CA certificate bundle: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    private function verifyCaBundle(string $filePath): bool
    {
        $content = file_get_contents($filePath);

        // Basic verification - check if it contains PEM certificates
        return str_contains($content, '-----BEGIN CERTIFICATE-----') &&
               str_contains($content, '-----END CERTIFICATE-----') &&
               strlen($content) > 100000; // Should be reasonably large
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2).' '.$units[$pow];
    }
}
