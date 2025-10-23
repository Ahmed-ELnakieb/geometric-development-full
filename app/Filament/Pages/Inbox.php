<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Webklex\PHPIMAP\ClientManager;

class Inbox extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    
    protected static ?string $navigationLabel = 'Inbox';
    
    protected static ?string $navigationGroup = 'Mail';
    
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.inbox';

    public $emails = [];
    public $selectedEmail = null;
    public $loading = false;
    public $error = null;
    public $stats = [
        'total' => 0,
        'unread' => 0,
    ];

    /**
     * Mount and load emails from IMAP
     */
    public function mount(): void
    {
        $this->loadEmails();
    }

    /**
     * Load emails from IMAP server
     */
    public function loadEmails(): void
    {
        try {
            $this->loading = true;
            $this->error = null;

            // Check if IMAP is configured
            if (!env('IMAP_HOST') || !env('IMAP_USERNAME')) {
                $this->error = 'IMAP not configured. Please add IMAP settings to your .env file.';
                $this->loading = false;
                return;
            }

            // Create IMAP client
            $cm = new ClientManager();
            $client = $cm->make([
                'host' => env('IMAP_HOST', 'imap.gmail.com'),
                'port' => env('IMAP_PORT', 993),
                'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
                'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                'username' => env('IMAP_USERNAME'),
                'password' => env('IMAP_PASSWORD'),
                'protocol' => env('IMAP_PROTOCOL', 'imap'),
            ]);

            // Connect to the server
            $client->connect();

            // Get inbox folder
            $folder = $client->getFolder('INBOX');

            // Get messages (limit to last 50)
            $messages = $folder->messages()
                ->all()
                ->limit(50)
                ->get();

            $this->emails = [];
            foreach ($messages as $message) {
                $this->emails[] = [
                    'uid' => $message->getUid(),
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom()[0]->mail ?? 'Unknown',
                    'from_name' => $message->getFrom()[0]->personal ?? $message->getFrom()[0]->mail ?? 'Unknown',
                    'date' => $message->getDate(),
                    'body_preview' => strip_tags(substr($message->getTextBody(), 0, 150)),
                    'is_read' => $message->hasFlag('Seen'),
                ];
            }

            // Calculate stats
            $this->stats['total'] = count($this->emails);
            $this->stats['unread'] = count(array_filter($this->emails, fn($email) => !$email['is_read']));

            $this->loading = false;

            Notification::make()
                ->title('Inbox loaded')
                ->body("{$this->stats['total']} emails found, {$this->stats['unread']} unread")
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->loading = false;

            Notification::make()
                ->title('Failed to load inbox')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * View email details
     */
    public function viewEmail(string $uid): void
    {
        try {
            $cm = new ClientManager();
            $client = $cm->make([
                'host' => env('IMAP_HOST', 'imap.gmail.com'),
                'port' => env('IMAP_PORT', 993),
                'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
                'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                'username' => env('IMAP_USERNAME'),
                'password' => env('IMAP_PASSWORD'),
                'protocol' => env('IMAP_PROTOCOL', 'imap'),
            ]);

            $client->connect();
            $folder = $client->getFolder('INBOX');
            $message = $folder->query()->getMessageByUid($uid);

            if ($message) {
                $this->selectedEmail = [
                    'uid' => $message->getUid(),
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom()[0]->mail ?? 'Unknown',
                    'from_name' => $message->getFrom()[0]->personal ?? $message->getFrom()[0]->mail ?? 'Unknown',
                    'to' => $message->getTo()[0]->mail ?? 'Unknown',
                    'date' => $message->getDate(),
                    'body_html' => $message->getHTMLBody(),
                    'body_text' => $message->getTextBody(),
                    'attachments' => $message->getAttachments()->count(),
                ];

                // Mark as read
                $message->setFlag('Seen');
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to load email')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Close email viewer
     */
    public function closeEmail(): void
    {
        $this->selectedEmail = null;
        $this->loadEmails(); // Refresh list
    }

    /**
     * Refresh inbox
     */
    public function refresh(): void
    {
        $this->loadEmails();
    }
}
