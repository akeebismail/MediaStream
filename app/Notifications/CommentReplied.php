<?php

namespace App\Notifications;

use App\Comment;
use App\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentReplied extends Notification implements ShouldBroadcast
{
    use Queueable;
    /**
     * @var Submission
     */
    private $submission;
    /**
     * @var Comment
     */
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param Submission $submission
     * @param Comment $comment
     */
    public function __construct(Submission $submission, Comment $comment)
    {
        //
        $this->submission = $submission;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->settings['notify_comments_replied']){
            return ['database','broadcast'];
        }
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage())
            ->title($this->comment->owner->username.' replied to your comment on "'.$this->submission->title.'":')
            ->subject('Your comment on"'.$this->submission->title.'" just got a new reply.')
            ->line('"'.$this->comment->body.'"')
            ->action('Reply', 'https://voten.co/'.$this->submission->slug)
            ->line('Thank you for being a part of our alpha program!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'url'    => '/c/'.$this->submission->channel_name.'/'.$this->submission->slug.'?comment='.$this->comment->id,
            'name'   => $this->comment->owner->username,
            'avatar' => $this->comment->owner->avatar,
            'body'   => '@'.$this->comment->owner->username.' replied to your comment on "'.$this->submission->title.'"',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data'       => $this->toArray($notifiable),
            'created_at' => now(),
            'read_at'    => null,
        ]);
    }
}
