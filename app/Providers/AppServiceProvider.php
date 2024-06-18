<?php

namespace App\Providers;

use App\Contract\AttributesFeature\Utils\AttributeExtractor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('tag', function ($app) {
            return new AttributeExtractor();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
