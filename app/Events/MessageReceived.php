<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.conversation.' . $this->message->conversation_id),
            new Channel('chat.session.' . $this->message->conversation->visitor_session_id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'whatsapp_message_id' => $this->message->whatsapp_message_id,
                'text' => $this->message->content,
                'type' => $this->message->direction === 'inbound' ? 'received' : 'sent',
                'sender_type' => $this->message->sender_type,
                'message_type' => $this->message->message_type,
                'media_url' => $this->message->media_url,
                'status' => $this->message->status,
                'timestamp' => $this->message->created_at->toISOString(),
                'metadata' => $this->message->metadata
            ],
            'conversation_id' => $this->message->conversation_id,
            'session_id' => $this->message->conversation->visitor_session_id
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.received';
    }
}
