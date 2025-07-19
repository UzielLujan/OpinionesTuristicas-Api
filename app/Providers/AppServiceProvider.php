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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            \DB::connection()->getPdo();
            \Log::info('Database connection successful!');
        } catch (\Exception $e) {
            \Log::error('Database connection failed: ' . $e->getMessage());
        }
    }
}
