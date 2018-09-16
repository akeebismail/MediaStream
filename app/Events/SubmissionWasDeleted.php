<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubmissionWasDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $submission;
    public $deletedByAuthor;

    /**
     * Create a new event instance.
     *
     * @param $submission
     * @param $deletedByAuthor
     */
    public function __construct($submission, $deletedByAuthor)
    {
        //
        $this->submission = $submission;
        $this->deletedByAuthor = $deletedByAuthor;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
