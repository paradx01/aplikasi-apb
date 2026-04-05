<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification; 
use NotificationChannels\WebPush\WebPushChannel; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**W
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Notification::extend('webpush', function ($app) {
            // Mengembalikan instance dari WebPushChannel
            return $app->make(WebPushChannel::class);
        });
    }
    
}
