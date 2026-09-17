<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $contentText;
    public $content;
    public $id;

   public function __construct($subjectText, $contentText, $id)
{
    $this->subjectText = $subjectText;
    $this->contentText = $contentText;
    $this->id = $id;
    // also expose as 'content' for blades expecting $content
    $this->content = $contentText;
}

public function build()
{
    return $this->subject($this->subjectText)
                ->view('emails.newsletter')
                ->with([
                    'content' => $this->contentText,
                    'contentText' => $this->contentText,
                    'subjectText' => $this->subjectText,
                    'subject' => $this->subjectText,
                    'id' => $this->id,
                ]);
}



}