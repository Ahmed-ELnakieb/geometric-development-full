<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingIndicator implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $conversationId;
    public string $sessionId;
    public string $senderType;
    public ?int $senderId;
    public bool $isTyping;

    /**
     * Create a new event instance.
     */
    public function __construct(
        string $conversationId,
        string $sessionId,
        string $senderType,
        ?int $senderId = null,
        bool $isTyping = true
    ) {
        $this->conversationId = $conversationId;
        $this->sessionId = $sessionId;
        $this->senderType = $senderType;
        $this->senderId = $senderId;
        $this->isTyping = $isTyping;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.conversation.' . $this->conversationId),
            new Channel('chat.session.' . $this->sessionId),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'session_id' => $this->sessionId,
            'sender_type' => $this->senderType,
            'sender_id' => $this->senderId,
            'is_typing' => $this->isTyping,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'typing.indicator';
    }
}
