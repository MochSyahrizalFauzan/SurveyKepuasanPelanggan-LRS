<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Blade::anonymousComponentPath(resource_path('views/clients/components'));
        Blade::anonymousComponentPath(resource_path('views/clients/components'), 'clients');
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');

        Blade::component('clients.components.survey.confirmation', 'clients.survey.confirmation');
        Blade::component('clients.components.survey.confirmation-receipt', 'clients.survey.confirmation-receipt');
        Blade::component('clients.components.survey.confirmation-audio', 'clients.survey.confirmation-audio');
        Blade::component('clients.components.survey.confirmation-smap-ticker', 'clients.survey.confirmation-smap-ticker');
        Blade::component('clients.components.survey-card', 'clients.survey-card');
        Blade::component('clients.components.footer', 'clients.footer');
    }
}
