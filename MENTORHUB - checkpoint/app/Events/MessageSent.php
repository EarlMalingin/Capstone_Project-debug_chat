<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        // Broadcast to the receiver
        if ($this->message->receiver_type === 'student') {
            $channels[] = new PrivateChannel('private-chat.student.' . $this->message->receiver_id);
        } else {
            $channels[] = new PrivateChannel('private-chat.tutor.' . $this->message->receiver_id);
        }

        // Broadcast to the sender (for instant feedback)
        if ($this->message->sender_type === 'student') {
            $channels[] = new PrivateChannel('private-chat.student.' . $this->message->sender_id);
        } else {
            $channels[] = new PrivateChannel('private-chat.tutor.' . $this->message->sender_id);
        }

        // Log the broadcast channels and message
        \Log::info('Broadcasting MessageSent event', [
            'channels' => array_map(function($ch) { return (string) $ch; }, $channels),
            'message' => $this->message->toArray(),
        ]);

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'sender_type' => $this->message->sender_type,
                'receiver_id' => $this->message->receiver_id,
                'receiver_type' => $this->message->receiver_type,
                'message' => $this->message->message,
                'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
                'is_read' => $this->message->is_read,
            ]
        ];
    }
}
