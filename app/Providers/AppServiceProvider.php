<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StripeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeService::class, function ($app) {
            return new StripeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
