<?php

namespace App\Listeners;

use App\Mail\ChangedAdminUserPasswordEmail;
use Illuminate\Support\Facades\Mail;

class SendToAdminAboutChangedPassword
{
    public function handle(object $event): void
    {
        Mail::to($event->adminUser->email)->send(new ChangedAdminUserPasswordEmail($event->adminUser, $event->newPassword));
    }

}
