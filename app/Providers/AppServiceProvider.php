<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Use Bootstrap 5 markup for paginator and a unified custom view across the app
        Paginator::useBootstrapFive();
        Paginator::defaultView('vendor.pagination.advanced');
        Paginator::defaultSimpleView('vendor.pagination.advanced');
    }
}
