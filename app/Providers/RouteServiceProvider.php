<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Path default setelah login (optional)
     */
    public const HOME = '/';

    /**
     * Boot routing
     */
    public function boot(): void
    {
        $this->routes(function () {

            // ===============================
            // WEB ROUTES
            // ===============================
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // ===============================
            // API ROUTES (INI YANG PENTING)
            // ===============================
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}