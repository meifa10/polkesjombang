<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
        // Locale Indonesia untuk Carbon
        Carbon::setLocale('id');

        // Support ngrok
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
            URL::forceRootUrl('https://' . request()->getHost());
        }
    }
}