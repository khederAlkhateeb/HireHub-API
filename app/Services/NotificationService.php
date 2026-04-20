<?php

namespace App\Services;

use App\Interfaces\NotificationInterface;

class NotificationService
{
    protected $driver;

    public function __construct(NotificationInterface $driver)
    {
        $this->driver = $driver;
    }

    public function send($user, $message)
    {
        return $this->driver->send($user, $message);
    }
}