<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse; // Importar para tipado

/**
 * @OA\Tag(
 * name="Authentication",
 * description="Autenticación a través de Supabase (OAuth/JWT Validation)"
 * )
 */
class SupabaseAuthController extends Controller
{
    /**
     * Valida un token JWT de Supabase, crea/actualiza el usuario localmente, y lo autentica en Laravel.
     *
     * @OA\Post(
     * path="/api/auth/supabase/handle",
     * tags={"Authentication"},
     * summary="Autenticación backend mediante token de Supabase.",
     * description="Recibe el token JWT obtenido por el cliente desde Supabase y lo valida para autenticar al usuario en la sesión de Laravel.",
     * @OA\RequestBody(
     * required=true,
     * description="El token de acceso de Supabase debe ser enviado en el encabezado 'Authorization: Bearer <token>'",
     * @OA\JsonContent(
     * @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiI...")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Usuario autenticado con éxito en Laravel.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Autenticado")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Token faltante o inválido.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Token inválido")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Email del usuario no disponible en la respuesta de Supabase.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Email no disponible")
     * )
     * )
     * )
     */
    public function handle(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token faltante'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Validar el token con Supabase
        $response = Http::withToken($token)->get(env('VITE_SUPABASE_URL') . '/auth/v1/user');

        if ($response->failed()) {
            return response()->json(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = $response->json();
        $email = $data['email'] ?? null;
        $name = $data['user_metadata']['name'] ?? 'Sin nombre';

        if (!$email) {
            return response()->json(['message' => 'Email no disponible'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Buscar o crear el usuario
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => bcrypt(\Illuminate\Support\Str::random(32))]
        );

        Auth::login($user);

        return response()->json(['message' => 'Autenticado'], JsonResponse::HTTP_OK);
    }
}
