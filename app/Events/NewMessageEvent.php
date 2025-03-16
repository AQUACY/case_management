<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param mixed $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // If we have a message with a specific ID, create a private channel for that conversation
        if (isset($this->data->message_id)) {
            return new PrivateChannel('message.' . $this->data->message_id);
        }

        // For general notifications or read receipts
        return new Channel('messages');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        // Determine the event type based on the data
        if (isset($this->data['type']) && $this->data['type'] === 'read_receipt') {
            return 'read-receipt';
        }

        return 'new-message';
    }

    public function broadcastWith()
    {
        // Handle both object data and array data
        if (is_object($this->data)) {
            // For MessageConversation objects
            return [
                'id' => $this->data->id,
                'message_id' => $this->data->message_id,
                'content' => $this->data->content,
                'sender_id' => $this->data->sender_id,
                'sender_type' => $this->data->sender_type,
                'sender_name' => isset($this->data->sender) ? $this->data->sender->name : null,
                'created_at' => $this->data->created_at,
                'is_read' => $this->data->is_read
            ];
        } else if (is_array($this->data)) {
            // For read receipts and other array data
            return $this->data;
        }

        // Fallback
        return ['data' => $this->data];
    }
}
