<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTranslationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunicationMethodController;
use App\Http\Controllers\SupabaseAuthController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonCardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\EvaluationController; // <-- NUEVO: Importa el controlador de Evaluaciones
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


// GRUPO DE RUTAS DE AUTENTICACIÓN (Prefijo: /api/auth)
Route::prefix('auth')->group(function () {
    // ----------------------------------------------------------------------
    // 1. Rutas PÚBLICAS de Auth (Login, Signup, Create Roles)
    // ----------------------------------------------------------------------
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup']);
    
    Route::post('create-admin', [AuthController::class, 'createAdmin']);
    Route::post('create-therapist', [AuthController::class, 'createTherapist']);
    
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
    });
});


// -------------------------------------------------------------------------
// 3. RUTAS PROTEGIDAS DE DATOS Y GESTIÓN
// -------------------------------------------------------------------------

// GRUPO DE ACCESO DE LECTURA (GET) - Roles: user, therapist, admin
Route::middleware(['auth:api', 'role:user,therapist,admin'])->group(function () {
    
    // CATEGORIES: Acceso de lectura (index, show)
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // COMMUNICATION METHODS: Acceso de lectura (index, show)
    Route::prefix('communication-methods')->group(function () {
        Route::get('/', [CommunicationMethodController::class, 'index']);
        Route::get('/{methodId}', [CommunicationMethodController::class, 'show']);
    });
    
    // CARDS: Acceso de lectura (index, show)
    Route::apiResource('cards', CardController::class)->only(['index', 'show']);
    Route::get('cards/uuid/{uuid}', [CardController::class, 'showByUuid']);

    // CARD TRANSLATIONS: Acceso de lectura (index, show)
    Route::apiResource('card-translations', CardTranslationController::class)->only(['index', 'show']);

    // LESSONS: Acceso de lectura (index, show)
    Route::apiResource('lessons', LessonController::class)->only(['index', 'show']);
    
    // LESSON CARDS: Acceso de lectura (index, show)
    Route::get('lesson-cards', [LessonCardController::class, 'index']); 
    // Show con claves compuestas: /api/lesson-cards/{lesson_id}/{card_id}
    Route::get('lesson-cards/{lesson_id}/{card_id}', [LessonCardController::class, 'show']);

    // EVALUATIONS: Acceso de lectura (index, show) <-- NUEVAS RUTAS DE LECTURA
    Route::apiResource('evaluations', EvaluationController::class)->only(['index', 'show']);
});


// GRUPO DE ACCESO DE ESCRITURA (POST, PUT, DELETE) - Roles: therapist, admin
Route::middleware(['auth:api', 'role:therapist,admin'])->group(function () {

    // CATEGORIES: Acceso de escritura
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    
    // COMMUNICATION METHODS: Acceso de escritura
    Route::prefix('communication-methods')->group(function () {
        Route::post('/', [CommunicationMethodController::class, 'store']);
        Route::put('/{methodId}', [CommunicationMethodController::class, 'update']);
        Route::delete('/{methodId}', [CommunicationMethodController::class, 'destroy']);
    });
    
    // CARDS: Acceso de escritura
    Route::apiResource('cards', CardController::class)->except(['index', 'show']);

    // CARD TRANSLATIONS: Acceso de escritura
    Route::apiResource('card-translations', CardTranslationController::class)->except(['index', 'show']);

    // LESSONS: Acceso de escritura
    Route::apiResource('lessons', LessonController::class)->except(['index', 'show']);
    
    // LESSON CARDS: Acceso de escritura
    Route::post('lesson-cards', [LessonCardController::class, 'store']);
    Route::put('lesson-cards/{lesson_id}/{card_id}', [LessonCardController::class, 'update']); 
    Route::patch('lesson-cards/{lesson_id}/{card_id}', [LessonCardController::class, 'update']);
    Route::delete('lesson-cards/{lesson_id}/{card_id}', [LessonCardController::class, 'destroy']);
    
    // EVALUATIONS: Acceso de escritura (store, update, destroy) <-- NUEVAS RUTAS DE ESCRITURA
    Route::apiResource('evaluations', EvaluationController::class)->except(['index', 'show']);
});
