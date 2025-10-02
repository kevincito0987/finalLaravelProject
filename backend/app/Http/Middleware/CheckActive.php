<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si hay un usuario autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // 2. Verificar el estado 'is_active' del usuario
            if (!$user->is_active) {
                // El usuario está inactivo, así que cerramos su sesión actual.
                Auth::logout();

                // Devolvemos una respuesta JSON clara y un código 403 (Prohibido)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tu cuenta ha sido desactivada. Por favor, contacta con el administrador del sistema.',
                ], 403);
            }
        }

        // Si el usuario no está autenticado, o si está autenticado y activo,
        // permitimos que la solicitud continúe.
        return $next($request);
    }
}
