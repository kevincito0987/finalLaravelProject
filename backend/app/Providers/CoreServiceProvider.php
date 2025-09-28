<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Core\Repositories\EloquentCommunicationMethodRepository;

/**
 * Este Service Provider registra los bindings entre interfaces de Core y sus implementaciones de Infraestructura.
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de aplicación.
     */
    public function register(): void
    {
        // Binding para la Inyección de Dependencias
        $this->app->bind(
            CommunicationMethodRepositoryInterface::class,
            EloquentCommunicationMethodRepository::class
        );

        // Puedes registrar otros bindings de Core/Interfaces aquí...
    }

    /**
     * Arranca cualquier servicio de aplicación.
     */
    public function boot(): void
    {
        //
    }
}
