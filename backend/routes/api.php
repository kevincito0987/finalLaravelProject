<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupabaseAuthController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup']);
    // ✅ Login/signup vía Supabase (Google/GitHub)
    Route::middleware('auth.supabase')->post('supabase/login', [SupabaseAuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
    Route::post('create-admin', [AuthController::class, 'createAdmin']);
    Route::post('create-therapist', [AuthController::class, 'createTherapist']);

});