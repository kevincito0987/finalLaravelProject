<?php

use App\Application\ServicesInterfaces\Translation\ITranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tu endpoint profesional protegido y listo para producción
Route::get('/test-translation', function (ITranslationService $translationService) {
    
    // Captura el idioma del slider mapeado por el SetLocaleMiddleware
    $currentLocale = App::getLocale(); 

    // 1. Palabra estática del Frontend (Cero JSONs)
    $frontendLabel = "Frutas";
    $translatedLabel = $translationService->translate(
        text: $frontendLabel, 
        targetLanguage: $currentLocale, 
        sourceLanguage: 'es'
    );

    // 2. Texto largo dinámico de la base de datos
    $dbContent = "La arquitectura limpia y el motor de IA local funcionan perfectamente sin archivos de configuración.";
    $translatedDbContent = $translationService->translate(
        text: $dbContent, 
        targetLanguage: $currentLocale, 
        sourceLanguage: 'es'
    );

    return response()->json([
        'status' => 'success',
        'config_mode' => '100% AI Dynamic (Zero JSONs)',
        'target_language' => $currentLocale,
        'frontend_component' => [
            'original' => $frontendLabel,
            'translated' => $translatedLabel
        ],
        'database_content' => [
            'original' => $dbContent,
            'translated' => $translatedDbContent
        ]
    ]);
});
