<?php

namespace App\Events;

use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    public $message;
    public $contact_id;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param $contact_id
     * @param $user_id
     */
    public function __construct(Message $message, $contact_id, $user_id)
    {
        //
        $this->message = $message;
        $this->contact_id = $contact_id;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.$this->user_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => (new MessageResource($this->message))->resolve(),
        ];
    }

}
