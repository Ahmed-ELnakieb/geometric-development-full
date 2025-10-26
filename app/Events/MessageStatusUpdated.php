<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;
    public string $oldStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message, string $oldStatus)
    {
        $this->message = $message;
        $this->oldStatus = $oldStatus;
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
            'message_id' => $this->message->id,
            'whatsapp_message_id' => $this->message->whatsapp_message_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->message->status,
            'conversation_id' => $this->message->conversation_id,
            'session_id' => $this->message->conversation->visitor_session_id,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.status.updated';
    }
}
