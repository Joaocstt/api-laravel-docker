<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(protected string $activationUrl, protected string $name) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ativação da sua Conta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activation',
            with: ['activationUrl' => $this->activationUrl, 'name' => $this->name]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
