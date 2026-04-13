<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(\App\Services\LayananEnkripsiUniversal::class, function ($app) {
            return new \App\Services\LayananEnkripsiUniversal();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Force timezone Laravel
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
    }
}