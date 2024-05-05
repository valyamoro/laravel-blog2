<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Mail\RegistryEmail;
use Illuminate\Support\Facades\Mail;

class SendRegisteredEmailListener
{
    public function handle(UserRegisteredEvent $event): void
    {
        Mail::to($event->adminUser->email)->send(new RegistryEmail($event->adminUser));
    }

}
