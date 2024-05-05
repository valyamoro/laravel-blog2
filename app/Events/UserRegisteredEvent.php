<?php

namespace App\Events;

use App\Models\AdminUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AdminUser $adminUser)
    {
    }
}
