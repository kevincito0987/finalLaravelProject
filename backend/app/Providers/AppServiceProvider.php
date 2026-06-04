<?php

namespace App\Providers;

use App\Application\ServicesInterfaces\Translation\ITranslationService;
use App\Infrastructure\Persistence\Services\Translation\LibreTranslateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registramos como Singleton para que no cree instancias HTTP repetitivas por petición
        $this->app->singleton(ITranslationService::class, LibreTranslateService::class);
    }

    public function boot(): void
    {
        //
    }
}
