<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentWasDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $submission;
    public $deletedByAuthor;

    /**
     * Create a new event instance.
     *
     * @param $comment
     * @param $submission
     * @param $deletedByAuthor
     */
    public function __construct($comment, $submission, $deletedByAuthor)
    {
        //
        $this->comment = $comment;
        $this->submission = $submission;
        $this->deletedByAuthor = $deletedByAuthor;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      //  return new PrivateChannel('channel-name');
        return ['submission.'.$this->submission->slug];
    }
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => (new CommentResource($this->comment))->resolve(),
        ];
    }
}
