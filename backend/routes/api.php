<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTranslationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunicationMethodController;
use App\Http\Controllers\SupabaseAuthController;
use App\Http\Controllers\LessonController; // <-- AÑADIDO
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
    // 1. Rutas PÚBLICAS de Auth (Login, Signup, Create Roles)
    // ESTAS RUTAS SON ACCESIBLES SIN TOKEN DE AUTENTICACIÓN
    // ----------------------------------------------------------------------
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup']);
    
    // Rutas de Creación de Roles (AHORA PÚBLICAS)
    Route::post('create-admin', [AuthController::class, 'createAdmin']); // NO REQUIERE TOKEN NI ROL
    Route::post('create-therapist', [AuthController::class, 'createTherapist']); // NO REQUIERE TOKEN NI ROL
    
    // RUTA DE INICIALIZACIÓN (Si la sigues usando)
    if (app()->environment('local')) {
        Route::post('initial-admin', [AuthController::class, 'createInitialAdmin']);
    }

    Route::middleware('auth.supabase')->post('supabase/login', [SupabaseAuthController::class, 'login']);

    // ----------------------------------------------------------------------
    // 2. Rutas Protegidas (Me, Logout) - Requieren SOLO autenticación
    // ----------------------------------------------------------------------
    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // El grupo 'role:admin' ya no contiene las rutas de creación.
        // Si necesitas otras rutas de admin, puedes dejarlas aquí.
        // Route::middleware(['role:admin'])->group(function () { ... });
    });
});


// -------------------------------------------------------------------------
// 3. RUTAS PROTEGIDAS DE DATOS Y GESTIÓN (CATEGORIES y COMMUNICATION METHODS)
// -------------------------------------------------------------------------

// GRUPO DE ACCESO DE LECTURA (GET) - Roles: user, therapist, admin (Todos los autenticados)
Route::middleware(['auth:api', 'role:user,therapist,admin'])->group(function () {
    
    // CATEGORIES: Acceso de lectura (index, show)
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // COMMUNICATION METHODS: Acceso de lectura (index, show)
    Route::prefix('communication-methods')->group(function () {
        Route::get('/', [CommunicationMethodController::class, 'index']); // Listar todos
        Route::get('/{methodId}', [CommunicationMethodController::class, 'show']); // Mostrar uno por ID
    });
    
    // CARDS: Acceso de lectura (index, show)
    Route::apiResource('cards', CardController::class)->only(['index', 'show']);
    // Ruta para simulación de RFID/UUID
    Route::get('cards/uuid/{uuid}', [CardController::class, 'showByUuid']);

    // CARD TRANSLATIONS: Acceso de lectura (index, show)
    Route::apiResource('card-translations', CardTranslationController::class)->only(['index', 'show']);

    // LESSONS: Acceso de lectura (index, show) <-- AÑADIDO
    Route::apiResource('lessons', LessonController::class)->only(['index', 'show']);
});


// GRUPO DE ACCESO DE ESCRITURA (POST, PUT, DELETE) - Roles: therapist, admin
// Requiere autenticación y el rol especificado.
Route::middleware(['auth:api', 'role:therapist,admin'])->group(function () {

    // CATEGORIES: Acceso de escritura (store, update, destroy)
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    
    // COMMUNICATION METHODS: Acceso de escritura (store, update, destroy)
    Route::prefix('communication-methods')->group(function () {
        Route::post('/', [CommunicationMethodController::class, 'store']); // Crear uno nuevo
        Route::put('/{methodId}', [CommunicationMethodController::class, 'update']); // Actualizar uno existente
        Route::delete('/{methodId}', [CommunicationMethodController::class, 'destroy']); // Eliminar uno
    });
    
    // CARDS: Acceso de escritura (store, update, destroy)
    Route::apiResource('cards', CardController::class)->except(['index', 'show']);

    // CARD TRANSLATIONS: Acceso de escritura (store, update, destroy)
    Route::apiResource('card-translations', CardTranslationController::class)->except(['index', 'show']);

    // LESSONS: Acceso de escritura (store, update, destroy) <-- AÑADIDO
    Route::apiResource('lessons', LessonController::class)->except(['index', 'show']);
});
