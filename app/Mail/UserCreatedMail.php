<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $password, string $resetUrl)
    {
        $this->user = $user;
        $this->password = $password;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Revoryx & Partners')
                    ->view('emails.user-created');
    }
}