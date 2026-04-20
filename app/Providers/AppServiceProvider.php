<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\NotificationInterface;
use App\Notifications\EmailNotification;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(NotificationInterface::class, EmailNotification::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
