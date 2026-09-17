<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact request: ' . $this->contact->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'contact' => $this->contact,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}