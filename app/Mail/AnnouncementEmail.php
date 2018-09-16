<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $title;
    private $heading;
    private $body;
    private $username;
    private $button_text;
    private $button_url;

    /**
     * Create a new message instance.
     *
     * @param $title
     * @param $heading
     * @param $body
     * @param $username
     * @param $button_text
     * @param $button_url
     */
    public function __construct($title, $heading, $body, $username, $button_text, $button_url)
    {
        //
        $this->title = $title;
        $this->heading = $heading;
        $this->body = $body;
        $this->username = $username;
        $this->button_text = $button_text;
        $this->button_url = $button_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->title;
        $heading = $this->heading;
        $content = $this->body;
        $username = $this->username;
        $button_text = $this->button_text;
        $button_url = $this->button_url;
        return $this->markdown('emails.announcement',
            compact('heading','content','username','button_text','button_url'))
            ->subject($this->title);
    }
}
