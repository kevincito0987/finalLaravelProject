<?php

namespace App\Providers;

use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\UserRepositoryInterface;
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
        //
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class,
            CommunicationMethodRepositoryInterface::class,
            EloquentCommunicationMethodRepository::class
        );

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