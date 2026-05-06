<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;
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
        // Bagikan 4 layanan secara acak ke view footer
        \Illuminate\Support\Facades\View::composer('components.footer', function ($view) {
            $view->with('footerServices', \App\Models\Service::inRandomOrder()->limit(4)->get());
        });
    }
}
