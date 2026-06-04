<?php

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Maneja la autorización basada en roles (OWASP A01: Broken Access Control).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles Lista de roles permitidos (ej: 'admin', 'therapist')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Si por algún motivo no está autenticado o no tiene modelo de relación cargado
        if (!$user || !$user->relationLoaded('role') && !$user->role) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autorizado. Inicio de sesión requerido.'
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        // 2. Validar si el nombre del rol del usuario está dentro de los permitidos
        if (!in_array($user->role->name, $roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acceso denegado. No tienes los privilegios necesarios para este recurso.'
            ], Response::HTTP_FORBIDDEN); // 403 Forbidden (OWASP estándar)
        }

        return $next($request);
    }
}