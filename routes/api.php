<?php

use App\Http\Controllers\Api\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// WhatsApp Webhook Routes
Route::prefix('whatsapp')->middleware('whatsapp.webhook')->group(function () {
    Route::get('webhook', [WhatsAppWebhookController::class, 'verify'])
        ->name('whatsapp.webhook.verify');
    
    Route::post('webhook', [WhatsAppWebhookController::class, 'handle'])
        ->name('whatsapp.webhook.handle');
});

// WhatsApp Chat API Routes
Route::prefix('whatsapp/chat')->middleware(['throttle:60,1'])->group(function () {
    Route::post('send', [App\Http\Controllers\Api\WhatsAppChatController::class, 'sendMessage'])
        ->name('whatsapp.chat.send');
    
    Route::get('ping', [App\Http\Controllers\Api\WhatsAppChatController::class, 'ping'])
        ->name('whatsapp.chat.ping');
    
    Route::get('history', [App\Http\Controllers\Api\WhatsAppChatController::class, 'getHistory'])
        ->name('whatsapp.chat.history');
    
    Route::post('typing', [App\Http\Controllers\Api\WhatsAppChatController::class, 'sendTypingIndicator'])
        ->name('whatsapp.chat.typing');
    
    Route::get('websocket-info', [App\Http\Controllers\Api\WhatsAppChatController::class, 'getWebSocketInfo'])
        ->name('whatsapp.chat.websocket-info');
});