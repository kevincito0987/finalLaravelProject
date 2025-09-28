<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Core\Services\Auth\RegisterUserService;
use App\Core\Services\User\CreateAdminService;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminRegisteredMail;
use App\Mail\TherapistRegisteredMail;

/**
 * @OA\Tag(
 * name="Auth",
 * description="Operaciones de Autenticación de Usuarios (Login, Registro, Perfil)"
 * )
 */

class AuthController extends Controller
{
    use ApiResponse;

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
        $data = $request->validated(); 
        
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Credenciales invalidas', 401);
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->accessToken;

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'email' => $user->email,
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
        
        // 2. Cargar el modelo Eloquent del usuario recién creado
        $userModel = Auth::getProvider()->retrieveById($userEntity->id);
        
        // CORRECCIÓN: Se usa el modelo recién creado ($userModel) para el correo, 
        // ya que la ruta de registro suele ser pública y $request->user() sería NULL.
        Mail::mailer('real')->to($userEntity->email)->queue(new UserRegisteredMail($userModel));

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
     * Crea un nuevo usuario con rol 'admin'.
     * Esta ruta debe estar protegida por auth:api y role:admin.
     * * @OA\Post(
     * path="/api/auth/create-admin",
     * operationId="createAdmin",
     * tags={"Administración de Usuarios"},
     * summary="Crea un nuevo usuario con rol de Administrador",
     * description="Solo accesible por un usuario autenticado con rol 'admin'. Requiere un token JWT válido.",
     * security={{"bearerAuth": {}}},
     * * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name","email","password","password_confirmation"},
     * @OA\Property(property="name", type="string", example="Nuevo Administrador"),
     * @OA\Property(property="email", type="string", format="email", example="nuevo.admin@ejemplo.com"),
     * @OA\Property(property="password", type="string", format="password", example="Password123#"),
     * @OA\Property(property="password_confirmation", type="string", format="password", example="Password123#")
     * )
     * ),
     * * @OA\Response(
     * response=201,
     * description="Administrador creado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Administrador creado correctamente"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="name", type="string", example="Nuevo Administrador"),
     * @OA\Property(property="email", type="string", format="email", example="nuevo.admin@ejemplo.com"),
     * @OA\Property(property="roles", type="array", @OA\Items(type="string", example="admin"))
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado (si falta el token JWT)"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso Prohibido (si el usuario autenticado no tiene el rol 'admin')"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación (ej. email duplicado, formato de password)"
     * )
     * )
     */
    public function createAdmin(SignupRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        
        // 1. Crear la entidad/modelo de usuario con el rol 'admin'
        $userEntity = $this->createAdminService->execute(
            $data['name'],
            $data['email'],
            $data['password'],
            'admin' // Rol específico
        );
        
        // CORRECCIÓN: Adaptación del patrón de retrieveById() para obtener el modelo completo
        // del administrador recién creado para pasarlo al Mailable.
        $adminModel = Auth::getProvider()->retrieveById($userEntity->id);

        // 2. Enviar correo. Se pasa el modelo del administrador recién creado.
        Mail::mailer('real')->to($userEntity->email)->queue(new AdminRegisteredMail($adminModel));
        
        // 3. Respuesta exitosa
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
        
        // El creador es el usuario autenticado (aunque no lo usaremos en el mailer)
        // $creatorUserModel = $request->user(); 
        
        $userEntity = $this->createAdminService->execute(
            $data['name'],
            $data['email'],
            $data['password'],
            'therapist' // Rol específico
        );

        // CORRECCIÓN: Para evitar el TypeError (NULL dado a un constructor que espera App\Models\User),
        // y por lógica de negocio, pasamos el modelo del terapeuta recién creado al Mailable.
        $therapistModel = Auth::getProvider()->retrieveById($userEntity->id);
        
        // Notificación: Se pasa el modelo del terapeuta recién creado
        Mail::mailer('real')->to($userEntity->email)->queue(new TherapistRegisteredMail($therapistModel));

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
