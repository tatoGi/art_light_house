<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SlugService;
use Illuminate\Support\Facades\Blade;
use App\View\Components\website\layout;
use App\View\Components\website\header;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SlugService::class, function ($app) {
            return new SlugService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('website.layout', layout::class);
                Blade::component('website.header', header::class);

    }
}
