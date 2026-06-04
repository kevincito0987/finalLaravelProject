<?php

namespace App\Infrastructure\Persistence\Services\Translation;

use App\Application\ServicesInterfaces\Translation\ITranslationService;
use Jefs42\LibreTranslate;
use Illuminate\Support\Facades\Log;

class LibreTranslateService implements ITranslationService
{
    private LibreTranslate $translator;

    public function __construct()
    {
        // Obtenemos los valores del .env con fallbacks seguros por si acaso
        $host = env('LIBRETRANSLATE_URL', 'http://project-laravel-libretranslate');
        $port = (int) env('LIBRETRANSLATE_PORT', 5000);

        // Inicializamos el SDK de PHP apuntando al microservicio Docker
        $this->translator = new LibreTranslate($host, $port);
    }

    public function translate(string $text, string $targetLanguage, string $sourceLanguage = 'es'): string
    {
        // Si el texto está vacío o el idioma destino es igual al origen, no gastamos procesamiento
        if (empty(trim($text)) || $targetLanguage === $sourceLanguage) {
            return $text;
        }

        try {
            // Configuramos dinámicamente el origen y destino para esta petición
            $this->translator->setSource($sourceLanguage);
            $this->translator->setTarget($targetLanguage);

            // Ejecuta la llamada HTTP interna al contenedor
            return $this->translator->translate($text);
        } catch (\Exception $e) {
            // OWASP A09: Registramos el fallo de infraestructura de forma segura
            Log::error("Error de comunicación con LibreTranslate Local: " . $e->getMessage());

            // Fallback Resiliente: Si el motor cae, devolvemos el texto original para no romper la app
            return $text; 
        }
    }
}