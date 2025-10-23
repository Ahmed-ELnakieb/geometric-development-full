<?php

namespace App\Listeners;

use App\Models\MailLog;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class LogSentEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event - Log all sent emails
     */
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->sent->getOriginalMessage();
            
            // Extract email details
            $from = $message->getFrom();
            $to = $message->getTo();
            $cc = $message->getCc();
            $bcc = $message->getBcc();
            $subject = $message->getSubject();
            $body = $message->getHtmlBody() ?? $message->getTextBody();
            
            // Get attachment information
            $attachments = [];
            foreach ($message->getAttachments() as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getName(),
                    'type' => $attachment->getContentType(),
                    'size' => $attachment->bodyToString() ? strlen($attachment->bodyToString()) : 0,
                ];
            }

            // Create mail log entry
            MailLog::create([
                'from' => $this->formatAddresses($from),
                'to' => $this->formatAddresses($to),
                'cc' => $this->formatAddresses($cc),
                'bcc' => $this->formatAddresses($bcc),
                'subject' => $subject,
                'body' => $body,
                'attachments' => !empty($attachments) ? $attachments : null,
                'status' => 'sent',
                'mailer' => config('mail.default'),
                'user_id' => Auth::id(),
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't throw exception to prevent email sending from failing
            \Log::error('Failed to log sent email: ' . $e->getMessage());
        }
    }

    /**
     * Format email addresses array to string
     */
    protected function formatAddresses(?array $addresses): ?string
    {
        if (empty($addresses)) {
            return null;
        }

        $formatted = [];
        foreach ($addresses as $email => $name) {
            $formatted[] = $name ? "$name <$email>" : $email;
        }

        return implode(', ', $formatted);
    }
}
