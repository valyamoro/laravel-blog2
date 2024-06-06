<?php

namespace App\Mail;

use App\Models\AdminUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangedAdminUserPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdminUser $adminUser,
        public string $newPassword,
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'У вашего аккаунта ' . $this->adminUser->username . ' был изменен пароль!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.changed_password',
        );
    }

}
