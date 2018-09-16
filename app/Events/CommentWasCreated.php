<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\CommentResource;
class CommentWasCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $submission;
    public $author;
    public $parentComment;

    /**
     * Create a new event instance.
     *
     * @param $comment
     * @param $submission
     * @param $author
     * @param $parentComment
     */
    public function __construct($comment, $submission, $author, $parentComment)
    {
        //
        $this->comment = $comment;
        $this->submission = $submission;
        $this->author = $author;
        $this->parentComment = $parentComment;
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
        return ['submission'.optional($this->submission)->slug];
    }

    public function broadcastWitth()
    {
        return [
            'data' => (new CommentResource($this->comment))->resolve(),
        ];
    }
}
