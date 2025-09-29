<?php

namespace App\Providers;

use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Core\Interfaces\EloquentCardRepository;
use App\Core\Interfaces\EloquentCategoryRepository;
use App\Core\Interfaces\UserRepositoryInterface; // Asumo que esta es la ruta correcta para la interfaz del usuario
use App\Core\Repositories\CardRepositoryInterface;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Core\Repositories\CategoryRepositoryInterface;
use App\Core\Repositories\EloquentCommunicationMethodRepository;
use App\Core\Repositories\EloquentUserRepository;
use App\Core\Services\CommunicationMethodService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ----------------------------------------------------
        // CÓDIGO CORREGIDO: Un 'bind' por cada par Interfaz/Clase
        // ----------------------------------------------------
        
        // Binding para Usuario
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class // Asume la implementación correcta
        );

        // Binding para Métodos de Comunicación
        $this->app->bind(
            CommunicationMethodRepositoryInterface::class,
            EloquentCommunicationMethodRepository::class // Asume la implementación correcta
        );
        
        // Binding para Categoría (el que estaba fallando)
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );

        // Binding para la Tarjeta de Comunicación
        $this->app->bind(
            CardRepositoryInterface::class,
            EloquentCardRepository::class
        );
        
        // ----------------------------------------------------
        // El resto de tu código queda igual
        // ----------------------------------------------------

        $this->app->singleton(CommunicationMethodService::class, function ($app) {
            return new CommunicationMethodService(
                $app->make(CommunicationMethodRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}