<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunicationMethodController;
use App\Http\Controllers\SupabaseAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

Route::get('/health', fn() => ['ok' => true]);
Route::get('/health-any-auth', fn() => ['ok' => true])->middleware(['auth:api', 'can:view-health']);
Route::get('/health-admin', fn() => ['ok' => true])->middleware(['auth:api', 'can:view-health-admin']);


Route::prefix('posts')->group(function () {
    Route::middleware(['throttle:api', 'auth:api', 'role:user,therapist,admin'])->group(function () {
    });

    //Escritor o administrador
    Route::middleware(['throttle:api', 'auth:api', 'role:therapist,admin'])->group(function () {
    });
});

Route::post('/supabase-auth', [SupabaseAuthController::class, 'handle']);


// GRUPO DE RUTAS DE AUTENTICACIÓN (Prefijo: /api/auth)
Route::prefix('auth')->group(function () {
    // ----------------------------------------------------------------------
    // 1. Rutas Públicas de Auth (Login, Signup) - Funcionan correctamente
    // ----------------------------------------------------------------------
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup']);
    Route::middleware('auth.supabase')->post('supabase/login', [SupabaseAuthController::class, 'login']);

    // ----------------------------------------------------------------------
    // 2. Rutas Protegidas (Me, Logout)
    // Se aplican directamente el middleware auth:api
    // ----------------------------------------------------------------------
    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::middleware(['role:admin'])->group(function () {
            Route::post('create-admin', [AuthController::class, 'createAdmin']); 
            Route::post('create-therapist', [AuthController::class, 'createTherapist']);
        });
    });

});

Route::get('/communication-methods', [CommunicationMethodController::class, 'index'])->middleware('auth:api');

Route::post('/communication-methods', [CommunicationMethodController::class, 'store'])->middleware('auth:api');

Route::put('/communication-methods/{id}', [CommunicationMethodController::class, 'update'])->middleware('auth:api');

Route::delete('/communication-methods/{id}', [CommunicationMethodController::class, 'destroy'])->middleware('auth:api');

Route::post('/media/upload', [MediaController::class, 'upload']);
Route::get('/media', [MediaController::class, 'index']);
Route::delete('/media/{id}', [MediaController::class, 'destroy']);
