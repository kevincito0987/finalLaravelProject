<?php

namespace App\Providers;

use App\Core\Interfaces\CardRepositoryInterface;
use App\Core\Interfaces\CardTranslationRepositoryInterface;
use App\Core\Interfaces\CategoryRepositoryInterface;
use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Core\Interfaces\EvaluationQuestionRepositoryInterface;
use App\Core\Interfaces\EvaluationRepositoryInterface;
use App\Core\Interfaces\LessonCardRepositoryInterface;
use App\Core\Interfaces\LessonRepositoryInterface;
use App\Core\Interfaces\MediaStorageInterface;
use App\Core\Interfaces\UserLessonRepositoryInterface;
use App\Core\Interfaces\UserProgressRepositoryInterface;
use App\Core\Interfaces\UserRepositoryInterface;
use App\Core\Repositories\EloquentCardRepository;
use App\Core\Repositories\EloquentCardTranslationRepository;
use App\Core\Repositories\EloquentCategoryRepository;
use App\Core\Repositories\EloquentCommunicationMethodRepository;
use App\Core\Repositories\EloquentEvaluationQuestionRepository;
use App\Core\Repositories\EloquentEvaluationRepository;
use App\Core\Repositories\EloquentLessonRepository;
use App\Core\Repositories\EloquentUserRepository;
use App\Core\Repositories\EloquentUserProgressRepository;
use App\Core\Repositories\LessonCardRepository;
use App\Core\Repositories\SupabaseMediaStorage;
use App\Core\Services\CardTranslationService;
use App\Core\Services\CommunicationMethodService;
use App\Core\Services\MediaUploader;
use App\Repositories\EloquentUserLessonRepository;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ----------------------------------------------------
        // 1. BINDINGS DE REPOSITORIOS (Interfaz a Implementación)
        // ----------------------------------------------------
        
        // Usuario
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        // Métodos de Comunicación
        $this->app->bind(
            CommunicationMethodRepositoryInterface::class,
            EloquentCommunicationMethodRepository::class
        );
        
        // Categoría
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );

        // Tarjeta de Comunicación (Card)
        $this->app->bind(
            CardRepositoryInterface::class,
            EloquentCardRepository::class
        );

        // Traducción de Tarjeta (CardTranslation)
        $this->app->bind(
            CardTranslationRepositoryInterface::class,
            EloquentCardTranslationRepository::class
        );
        
        // Lección (Lesson) - ¡NUEVO BINDING!
        $this->app->bind(
            LessonRepositoryInterface::class,
            EloquentLessonRepository::class
        );

        $this->app->bind(
            LessonCardRepositoryInterface::class,
            LessonCardRepository::class
        );

        $this->app->bind(
            EvaluationRepositoryInterface::class,
            EloquentEvaluationRepository::class
        );

        $this->app->bind(
            EvaluationQuestionRepositoryInterface::class,
            EloquentEvaluationQuestionRepository::class
        );

        $this->app->bind(
            UserLessonRepositoryInterface::class,
            EloquentUserLessonRepository::class
        );

        // Binding para UserProgress
        $this->app->bind(
            UserProgressRepositoryInterface::class,
            EloquentUserProgressRepository::class
        );



        // ----------------------------------------------------
        // 2. BINDING PARA STORAGE (Resuelve el BindingResolutionException)
        // ----------------------------------------------------
        // Vinculamos la interfaz general de almacenamiento con su implementación específica.
        $this->app->bind(
            MediaStorageInterface::class,
            SupabaseMediaStorage::class
        );
        
        // ----------------------------------------------------
        // 3. SINGLETONS DE SERVICIOS (Con inyección de dependencias manual)
        // ----------------------------------------------------

        // Servicio de Métodos de Comunicación
        $this->app->singleton(CommunicationMethodService::class, function ($app) {
            return new CommunicationMethodService(
                $app->make(CommunicationMethodRepositoryInterface::class)
            );
        });

        // Servicio de Subida de Media (depende de MediaStorageInterface, que ya vinculamos)
        $this->app->singleton(MediaUploader::class, function ($app) {
            return new MediaUploader(
                $app->make(MediaStorageInterface::class)
            );
        });

        // Servicio de Traducción de Tarjetas (Necesita su repositorio y el MediaUploader)
        // Agregamos este binding para que se pueda resolver en el controlador (CardTranslationController)
        $this->app->singleton(CardTranslationService::class, function ($app) {
            return new CardTranslationService(
                $app->make(CardTranslationRepositoryInterface::class),
                $app->make(MediaUploader::class) // Ya está resuelto como Singleton arriba
            );
        });

        // Nota: LessonService se podría añadir aquí, si ya lo tuviéramos definido:
        /*
        $this->app->singleton(LessonService::class, function ($app) {
            return new LessonService(
                $app->make(LessonRepositoryInterface::class)
            );
        });
        */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
