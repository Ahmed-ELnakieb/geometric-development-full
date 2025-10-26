<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\DownloadCaCertificates::class,
                \App\Console\Commands\WhatsAppDebug::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL key length issues
        Schema::defaultStringLength(191);
        
        // Register email logging listener
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSent::class,
            \App\Listeners\LogSentEmail::class,
        );
        
        // Add minimal script to Filament admin pages to prevent PWA interference
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => Blade::render('
                <script>
                    // Simple PWA cleanup for admin pages
                    if ("serviceWorker" in navigator) {
                        // Unregister service workers on admin pages only
                        navigator.serviceWorker.getRegistrations().then(function(registrations) {
                            registrations.forEach(function(registration) {
                                registration.unregister().catch(function(error) {
                                    console.log("Service worker unregistration failed:", error);
                                });
                            });
                        }).catch(function(error) {
                            console.log("Failed to get service worker registrations:", error);
                        });
                    }
                </script>
            ')
        );
    }
}
