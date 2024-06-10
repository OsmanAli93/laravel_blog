<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );
    }
}
