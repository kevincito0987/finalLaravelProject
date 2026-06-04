<?php

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Intercepta la petición para fijar el idioma global del sistema.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Buscamos el header 'Accept-Language' enviado por el frontend (ej: 'en' o 'es')
        // Si el frontend no lo envía, usamos el idioma por defecto del sistema ('es')
        $locale = $request->header('Accept-Language', config('app.locale'));

        // 2. Validamos que sea uno de tus dos idiomas soportados para evitar strings maliciosos
        if (in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}