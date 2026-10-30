<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Paksa HTTPS di production / Vercel agar browser tidak memunculkan peringatan "Informasi yang Anda kirimkan tidak aman"
        if (config('app.env') === 'production' || isset($_SERVER['VERCEL']) || env('VERCEL') == '1') {
            URL::forceScheme('https');
        }
    }
}
