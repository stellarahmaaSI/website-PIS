<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Selipkan trik ini di dalam fungsi register
        if (isset($_ENV['VERCEL_ENV'])) {
            $viewCachePath = '/tmp/framework/views';
            
            if (!is_dir($viewCachePath)) {
                mkdir($viewCachePath, 0755, true);
            }
            
            // Set konfigurasi view compiled secara paksa di level runtime
            config(['view.compiled' => $viewCachePath]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}