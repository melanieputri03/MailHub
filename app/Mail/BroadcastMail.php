<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailNama;
    public string $emailSubject;
    public string $emailBody;

    // Create a new message instance.
    public function __construct(string $nama, string $subject, string $body)
    {
        $this->emailNama    = $nama;
        $this->emailSubject = $subject;
        $this->emailBody    = $body;
    }

    // Get the message envelope.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    // Get the message content definition.
    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast',
        );
    }

    //Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}