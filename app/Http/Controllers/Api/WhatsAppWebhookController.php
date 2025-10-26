<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppWebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    private WhatsAppWebhookHandler $webhookHandler;

    public function __construct(WhatsAppWebhookHandler $webhookHandler)
    {
        $this->webhookHandler = $webhookHandler;
    }

    /**
     * Handle incoming WhatsApp webhook
     */
    public function handle(Request $request): Response
    {
        Log::info('WhatsApp webhook received', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'content_length' => strlen($request->getContent())
        ]);

        return $this->webhookHandler->processWebhookEvent($request);
    }

    /**
     * Handle webhook verification (GET request)
     */
    public function verify(Request $request): Response
    {
        Log::info('WhatsApp webhook verification requested', [
            'query' => $request->query()
        ]);

        return $this->webhookHandler->processWebhookEvent($request);
    }
}
