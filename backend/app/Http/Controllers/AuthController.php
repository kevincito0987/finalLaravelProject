<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Core\Services\Auth\RegisterUserService;
use App\Core\Services\User\CreateAdminService;
use App\Http\Requests\Auth\SignupRequest; // Usar FormRequest para validaciones
use App\Http\Requests\Auth\LoginRequest; // Crear este FormRequest
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // Usar Request para me/logout, FormRequest para login/signup
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Tag(
 * name="Auth",
 * description="Operaciones de Autenticación de Usuarios (Login, Registro, Perfil)"
 * )
 */

class AuthController extends Controller
{
    use ApiResponse;

    // Inyectar el servicio para el registro
    public function __construct(
        private readonly RegisterUserService $registerUserService,
        private readonly CreateAdminService $createAdminService
    ) {}
    
    // ----------------------------------------------------------------------
    // LOGIN
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/auth/login",
     * tags={"Auth"},
     * summary="Login y emisión de token",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email","password"},
     * @OA\Property(property="email", type="string", format="email", example="user@test.com"),
     * @OA\Property(property="password", type="string", minLength=8, example="Pa55Word123$.")
     * )
     * ),
     * @OA\Response(
     * response=200, description="OK",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", nullable=true),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="token_type", type="string", example="Bearer"),
     * @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
     * @OA\Property(property="user", type="object",
     * @OA\Property(property="email", type="string", example="user@test.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"user"})
     * )
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login(LoginRequest $request)
    {
        // La validación se hace en LoginRequest
        $data = $request->validated(); 
        
        // La autenticación de Laravel se mantiene en el Controller (es un detalle de infraestructura)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Credenciales invalidas', 401);
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->accessToken;

        // Notificación - Orquestación
        // Aquí puedes seguir usando el Mailer de Laravel o inyectar un NotifierService
        Mail::mailer('real')->to($user->email)->queue(new UserRegisteredMail($user)); 

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'email' => $user->email,
                // Si la Entidad no incluye esta info, puedes usar el Model para esta capa de presentación
                'roles' => $user->roles()->pluck('name'), 
            ]
        ]);
    }

    // ----------------------------------------------------------------------
    // SIGNUP (USUARIO POR DEFECTO)
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/auth/signup",
     * tags={"Auth"},
     * summary="Registro de usuario base (rol: user)",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email", "password", "password_confirmation"},
     * @OA\Property(property="name", type="string", example="Ada Lovelace"),
     * @OA\Property(property="email", type="string", format="email", example="ada@example.com"),
     * @OA\Property(property="password", type="string", minLength=8, example="Pa55Word123$.", description="Contraseña de al menos 8 caracteres"),
     * @OA\Property(property="password_confirmation", type="string", example="Pa55Word123$.", description="Confirmación de la contraseña")
     * )
     * ),
     * @OA\Response(response=201, description="Creado", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="name", type="string", example="Ada Lovelace"),
     * @OA\Property(property="email", type="string", example="ada@example.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"user"})
     * )
     * )
     * ),
     * @OA\Response(response=422, description="Error de Validación")
     * )
     */
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        
        // 1. Llamar al Servicio de Core (Lógica de Negocio)
        $userEntity = $this->registerUserService->execute(
            $data['name'],
            $data['email'],
            $data['password']
        );
        
        // Opcional: Cargar el modelo Eloquent si necesitas usarlo para el token/respuesta de Laravel
        $userModel = Auth::getProvider()->retrieveById($userEntity->id);

        return $this->success([
            'name' => $userEntity->name,
            'email' => $userEntity->email,
            'roles' => $userEntity->roles,
        ], 'Usuario creado correctamente', 201);
    }
    
    // ----------------------------------------------------------------------
    // CREATE ADMIN
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/auth/admin",
     * tags={"Auth"},
     * summary="Crear un Administrador (Rol: admin)",
     * description="Endpoint utilizado por un administrador existente para crear otro administrador. Requiere protección de ruta por rol.",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email", "password", "password_confirmation"},
     * @OA\Property(property="name", type="string", example="Admin Supremo"),
     * @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     * @OA\Property(property="password", type="string", minLength=8, example="Pa55Word123$.", description="Contraseña de al menos 8 caracteres"),
     * @OA\Property(property="password_confirmation", type="string", example="Pa55Word123$.", description="Confirmación de la contraseña")
     * )
     * ),
     * @OA\Response(response=201, description="Administrador creado", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="name", type="string", example="Admin Supremo"),
     * @OA\Property(property="email", type="string", example="admin@example.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"admin"})
     * )
     * )
     * ),
     * @OA\Response(response=403, description="Prohibido (solo Admin)"),
     * @OA\Response(response=422, description="Error de Validación")
     * )
     */
    public function createAdmin(SignupRequest $request)
    {
        $data = $request->validated();
        
        // Usar el servicio especializado para crear con un rol distinto
        $userEntity = $this->createAdminService->execute(
            $data['name'],
            $data['email'],
            $data['password'],
            'admin' // Rol específico
        );
        
        // Notificación y respuesta
        // Mail::mailer('real')->to($userEntity->email)->queue(new UserRegisteredMail($userModel));
        
        return $this->success([
            'name' => $userEntity->name,
            'email' => $userEntity->email,
            'roles' => $userEntity->roles,
        ], 'Administrador creado correctamente', 201);
    }

    // ----------------------------------------------------------------------
    // CREATE THERAPIST
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/auth/therapist",
     * tags={"Auth"},
     * summary="Crear un Terapeuta (Rol: therapist)",
     * description="Endpoint utilizado por un administrador o terapeuta para crear un nuevo terapeuta. Requiere protección de ruta por rol.",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email", "password", "password_confirmation"},
     * @OA\Property(property="name", type="string", example="Doctor Strange"),
     * @OA\Property(property="email", type="string", format="email", example="therapy@example.com"),
     * @OA\Property(property="password", type="string", minLength=8, example="Pa55Word123$.", description="Contraseña de al menos 8 caracteres"),
     * @OA\Property(property="password_confirmation", type="string", example="Pa55Word123$.", description="Confirmación de la contraseña")
     * )
     * ),
     * @OA\Response(response=201, description="Terapeuta creado", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="name", type="string", example="Doctor Strange"),
     * @OA\Property(property="email", type="string", example="therapy@example.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"therapist"})
     * )
     * )
     * ),
     * @OA\Response(response=403, description="Prohibido (solo Admin/Therapist)"),
     * @OA\Response(response=422, description="Error de Validación")
     * )
     */
    public function createTherapist(SignupRequest $request)
    {
        $data = $request->validated();
        
        $userEntity = $this->createAdminService->execute(
            $data['name'],
            $data['email'],
            $data['password'],
            'therapist' // Rol específico
        );
        
        return $this->success([
            'name' => $userEntity->name,
            'email' => $userEntity->email,
            'roles' => $userEntity->roles,
        ], 'Terapeuta creado correctamente', 201);
    }

    // ----------------------------------------------------------------------
    // ME
    // ----------------------------------------------------------------------
    /**
     * @OA\Get(
     * path="/api/auth/me",
     * tags={"Auth"},
     * summary="Perfil básico del usuario autenticado",
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="OK", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="name", type="string", example="User Name"),
     * @OA\Property(property="email", type="string", example="user@example.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"user"})
     * )
     * )
     * ),
     * @OA\Response(response=401, description="No Autenticado")
     * )
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return $this->success([
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles()->pluck('name'),
        ]);
    }

    // ----------------------------------------------------------------------
    // LOGOUT
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/auth/logout",
     * tags={"Auth"},
     * summary="Cerrar sesión (revoca token)",
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="OK", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Sesión cerrada correctamente.")
     * )
     * )
     * )
     */
    public function logout(Request $request)
    {
        // Usando Passport para revocar el token actual
        $request->user()->token()->revoke();
        return $this->success(null, "Sesión cerrada correctamente.");
    }
}
