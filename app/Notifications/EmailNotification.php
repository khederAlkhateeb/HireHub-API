<?php

namespace App\Notifications;

use App\Interfaces\NotificationInterface;

class EmailNotification implements NotificationInterface
{
    public function send($user, $message)
    {
        return "Email sent to " . $user->email . " | Message: " . $message;
    }
}
