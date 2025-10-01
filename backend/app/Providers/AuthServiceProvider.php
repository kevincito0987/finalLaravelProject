<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Policies\CommunicationMethodPolicy;
use App\Policies\CardPolicy;
use App\Policies\CardTranslationPolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\EvaluationQuestionPolicy;
use App\Policies\LessonCardPolicy;
use App\Policies\LessonPolicy;
use App\Policies\UserLessonPolicy;

class AuthServiceProvider extends ServiceProvider
{
    //Policia de las politicas
    protected $policies = [
        CommunicationMethodPolicy::class,
        CardPolicy::class,
        CardTranslationPolicy::class,
        LessonPolicy::class,
        LessonCardPolicy::class,
        EvaluationPolicy::class,
        EvaluationQuestionPolicy::class,
        UserLessonPolicy::class
     ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //Definir Tokens

        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDay(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        //Gate 
        Gate::define('view-health', function (User $user) {
            return $user->hasRole(['therapist', 'user']);
        });

        Gate::define('view-health-admin', function (User $user) {
            return $user->hasRole(['therapist', 'admin']) || $user->tokenCan('posts.admin');
        });

        // NO RECOMENNDADO: PERO UTIL PARA PRUEBAS
        // Gate::before(function (User $user, string $ability) {
        //     return $user->hasRole(['admin']) ? true : null; //true -> concede los permisos
        // });


        //Scopes
        //recurso.accion
        Passport::tokensCan([
            'posts.read' => 'Leer posts',
            'posts.write' => 'Crear o Editar posts',
            'posts.delete' => 'Puede eliminar posts',
            'posts.admin' => 'Acceso VIP',
        ]);

        Passport::defaultScopes([
            'posts.read',
        ]);
    }
}