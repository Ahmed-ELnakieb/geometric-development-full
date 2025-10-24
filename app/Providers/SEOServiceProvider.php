<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SEOService;

class SEOServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SEOService::class, function ($app) {
            return new SEOService();
        });
    }

    public function boot()
    {
        //
    }
}