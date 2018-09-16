<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentWasPatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $submission;

    /**
     * Create a new event instance.
     *
     * @param $comment
     * @param $submission
     */
    public function __construct($comment, $submission)
    {
        //
        $this->comment = $comment;
        $this->submission = $submission;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        return ['submission.'.$this->submission->slug];
    }

    public function broadCastWith()
    {
        return [
            'data' => (new CommentResource($this->comment))->resolve(),
        ];
    }
}
