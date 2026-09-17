<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $renderedHtml;

    public function __construct(string $html)
    {
        $this->renderedHtml = $html;
    }

    public function build()
    {
        return $this->subject('Réinitialisation de votre mot de passe - Revoryx & Partners')
            ->html($this->renderedHtml);
    }
}