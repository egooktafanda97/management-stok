<?php

namespace App\Providers;

use App\Utils\ErrorResponse;
use Illuminate\Support\ServiceProvider;

class ErrorResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ErrorResponse::class, function ($app) {
            return ErrorResponse::getInstance();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
