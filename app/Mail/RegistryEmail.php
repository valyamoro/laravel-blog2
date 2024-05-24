<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Model $item) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Вам был предоставлен доступ в систему!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.registry',
        );
    }

}
