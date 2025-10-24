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
                'validate_cert' => env('IMAP_VALIDATE_CERT', false),
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
                // Convert IMAP date to Carbon instance, then to ISO string for Livewire
                $date = $message->getDate();
                $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
                
                // Get from address and convert to string
                $fromAddress = $message->getFrom();
                // Convert to array if it's an Attribute object
                $fromArray = is_array($fromAddress) ? $fromAddress : (is_object($fromAddress) ? $fromAddress->toArray() : []);
                if ($fromArray && is_array($fromArray) && count($fromArray) > 0) {
                    $firstFrom = $fromArray[0];
                    $fromMail = isset($firstFrom->mail) ? (string)$firstFrom->mail : (isset($firstFrom->mailbox) && isset($firstFrom->host) ? $firstFrom->mailbox . '@' . $firstFrom->host : 'Unknown');
                    $fromName = isset($firstFrom->personal) ? (string)$firstFrom->personal : $fromMail;
                } else {
                    $fromMail = 'Unknown';
                    $fromName = 'Unknown';
                }
                
                $this->emails[] = [
                    'uid' => (string)$message->getUid(),
                    'subject' => (string)($message->getSubject() ?: 'No Subject'),
                    'from' => $fromMail,
                    'from_name' => $fromName,
                    'date' => $carbonDate->toISOString(),
                    'body_preview' => (string)strip_tags(substr((string)$message->getTextBody(), 0, 150)),
                    'is_read' => (bool)$message->hasFlag('Seen'),
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
                'validate_cert' => env('IMAP_VALIDATE_CERT', false),
                'username' => env('IMAP_USERNAME'),
                'password' => env('IMAP_PASSWORD'),
                'protocol' => env('IMAP_PROTOCOL', 'imap'),
            ]);

            $client->connect();
            $folder = $client->getFolder('INBOX');
            $message = $folder->query()->getMessageByUid($uid);

            if ($message) {
                // Convert IMAP date to Carbon instance, then to ISO string for Livewire
                $date = $message->getDate();
                $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
                
                // Get addresses and convert to strings
                $fromAddress = $message->getFrom();
                // Convert to array if it's an Attribute object
                $fromArray = is_array($fromAddress) ? $fromAddress : (is_object($fromAddress) ? $fromAddress->toArray() : []);
                if ($fromArray && is_array($fromArray) && count($fromArray) > 0) {
                    $firstFrom = $fromArray[0];
                    $fromMail = isset($firstFrom->mail) ? (string)$firstFrom->mail : (isset($firstFrom->mailbox) && isset($firstFrom->host) ? $firstFrom->mailbox . '@' . $firstFrom->host : 'Unknown');
                    $fromName = isset($firstFrom->personal) ? (string)$firstFrom->personal : $fromMail;
                } else {
                    $fromMail = 'Unknown';
                    $fromName = 'Unknown';
                }
                
                $toAddress = $message->getTo();
                // Convert to array if it's an Attribute object
                $toArray = is_array($toAddress) ? $toAddress : (is_object($toAddress) ? $toAddress->toArray() : []);
                if ($toArray && is_array($toArray) && count($toArray) > 0) {
                    $firstTo = $toArray[0];
                    $toMail = isset($firstTo->mail) ? (string)$firstTo->mail : (isset($firstTo->mailbox) && isset($firstTo->host) ? $firstTo->mailbox . '@' . $firstTo->host : 'Unknown');
                } else {
                    $toMail = 'Unknown';
                }
                
                // Get body content and ensure it's a string
                try {
                    $htmlBody = $message->getHTMLBody();
                    $htmlBodyStr = is_string($htmlBody) ? $htmlBody : (is_object($htmlBody) && method_exists($htmlBody, '__toString') ? (string)$htmlBody : '');
                } catch (\Exception $e) {
                    $htmlBodyStr = '';
                }
                
                try {
                    $textBody = $message->getTextBody();
                    $textBodyStr = is_string($textBody) ? $textBody : (is_object($textBody) && method_exists($textBody, '__toString') ? (string)$textBody : '');
                } catch (\Exception $e) {
                    $textBodyStr = '';
                }
                
                try {
                    $attachmentCount = $message->getAttachments()->count();
                } catch (\Exception $e) {
                    $attachmentCount = 0;
                }
                
                $this->selectedEmail = [
                    'uid' => (string)$message->getUid(),
                    'subject' => (string)($message->getSubject() ?: 'No Subject'),
                    'from' => $fromMail,
                    'from_name' => $fromName,
                    'to' => $toMail,
                    'date' => $carbonDate->toISOString(),
                    'body_html' => $htmlBodyStr,
                    'body_text' => $textBodyStr,
                    'attachments' => (int)$attachmentCount,
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
