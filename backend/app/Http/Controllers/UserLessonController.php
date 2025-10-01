<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserLessonRequest;
use App\Http\Requests\UpdateUserLessonRequest;
use App\Models\UserLesson;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 * name="Progreso de Lecciones",
 * description="Gestión del progreso de lecciones por usuario (UserLesson)"
 * )
 * * @OA\Schema(
 * schema="UserLesson",
 * title="Progreso de Lección del Usuario",
 * description="Representa el registro de progreso de una lección por un usuario.",
 * @OA\Property(property="id", type="integer", description="ID único del registro de progreso."),
 * @OA\Property(property="user_id", type="integer", description="ID del usuario que realiza el progreso."),
 * @OA\Property(property="lesson_id", type="integer", description="ID de la lección asociada."),
 * @OA\Property(property="completed_at", type="string", format="date-time", nullable=true, description="Marca de tiempo cuando la lección fue completada. Nulo si no está completada."),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación del registro."),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de la última actualización del registro."),
 * @OA\Property(property="lesson", ref="#/components/schemas/Lesson", description="Relación con el objeto Lección (incluido en la respuesta).")
 * )
 * * @OA\Schema(
 * schema="StoreUserLessonRequest",
 * title="Crear Progreso de Lección",
 * required={"lesson_id"},
 * @OA\Property(property="lesson_id", type="integer", description="El ID de la lección que el usuario está iniciando o completando."),
 * @OA\Property(property="is_completed", type="boolean", nullable=true, description="Indica si la lección se está marcando como completada al crear el registro (opcional, por defecto es false).")
 * )
 * * @OA\Schema(
 * schema="UpdateUserLessonRequest",
 * title="Actualizar Progreso de Lección",
 * description="Datos para actualizar un registro de progreso existente.",
 * @OA\Property(property="is_completed", type="boolean", nullable=true, description="Marcar la lección como completada (true).")
 * )
 * * @OA\Schema(
 * schema="Lesson",
 * title="Lesson Schema (Simple)",
 * description="Esquema simplificado de la lección para la relación.",
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="title", type="string")
 * )
 */
class UserLessonController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/user-lessons",
     * tags={"Progreso de Lecciones"},
     * summary="Listar el progreso de lecciones del usuario autenticado",
     * description="Recupera todos los registros de UserLesson asociados al usuario autenticado, incluyendo la información de la lección.",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Lista exitosa del progreso del usuario",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/UserLesson")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $userLessons = UserLesson::where('user_id', $userId)
            ->with('lesson')
            ->get();

        return response()->json($userLessons);
    }

    /**
     * @OA\Post(
     * path="/api/user-lessons",
     * tags={"Progreso de Lecciones"},
     * summary="Crea un nuevo registro de progreso (iniciar o completar lección)",
     * description="Crea un registro de UserLesson. Si ya existe un registro para esta lección y usuario, la validación fallará (422).",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/StoreUserLessonRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Progreso de lección creado/iniciado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Progreso de lección iniciado."),
     * @OA\Property(property="progress", ref="#/components/schemas/UserLesson")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación (e.g., lesson_id no existe o ya existe progreso para el usuario)"
     * )
     * )
     */
    public function store(StoreUserLessonRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // El 'user_id' está garantizado por Auth::id() ya que el Request requiere autenticación.
        $isCompleted = $data['is_completed'] ?? false;
        
        $userLesson = UserLesson::create([
            'user_id' => Auth::id(), 
            'lesson_id' => $data['lesson_id'],
            'completed_at' => $isCompleted ? Carbon::now() : null,
        ]);

        return response()->json([
            'message' => $isCompleted ? 'Lección marcada como completada.' : 'Progreso de lección iniciado.',
            'progress' => $userLesson->load('lesson')
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/api/user-lessons/{user_lesson}",
     * tags={"Progreso de Lecciones"},
     * summary="Mostrar el progreso de una lección específica por su ID",
     * description="Muestra un registro de progreso individual (UserLesson). Requiere que el registro pertenezca al usuario autenticado.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_lesson",
     * in="path",
     * required=true,
     * description="ID del registro de progreso (UserLesson)",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Detalle del progreso exitoso",
     * @OA\JsonContent(ref="#/components/schemas/UserLesson")
     * ),
     * @OA\Response(
     * response=404,
     * description="Progreso no encontrado o no pertenece al usuario"
     * )
     * )
     */
    public function show(UserLesson $userLesson): JsonResponse
    {
        // Se utiliza el método 'findOrFail' en el modelo o una Policy
        // para asegurar que el usuario autenticado es el dueño antes de cargarlo.
        if ($userLesson->user_id !== Auth::id()) {
            // Usamos abort(404) para evitar la enumeración de IDs ajenos.
            abort(404, 'Progreso no encontrado o acceso denegado.'); 
        }

        return response()->json($userLesson->load('lesson'));
    }

    /**
     * @OA\Put(
     * path="/api/user-lessons/{user_lesson}",
     * tags={"Progreso de Lecciones"},
     * summary="Actualiza el progreso de una lección existente",
     * description="Actualiza un registro de UserLesson, típicamente para marcar 'is_completed' como true. La autorización para modificar solo el propio progreso se maneja en el FormRequest.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_lesson",
     * in="path",
     * required=true,
     * description="ID del registro de progreso (UserLesson) a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateUserLessonRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Progreso de lección actualizado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Progreso actualizado exitosamente."),
     * @OA\Property(property="progress", ref="#/components/schemas/UserLesson")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Acción no autorizada (el progreso no pertenece al usuario)"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=400,
     * description="Error de lógica de negocio (ej: intentar desmarcar una lección ya completada)"
     * )
     * )
     */
    public function update(UpdateUserLessonRequest $request, UserLesson $userLesson): JsonResponse
    {
        $data = $request->validated();
        
        // 1. Manejar la actualización de lesson_id si se envía
        if (isset($data['lesson_id'])) {
            $userLesson->lesson_id = $data['lesson_id'];
        }

        // 2. Manejar el marcado/desmarcado de la finalización
        if (isset($data['is_completed'])) {
            $isCompleted = (bool) $data['is_completed'];

            if ($isCompleted && is_null($userLesson->completed_at)) {
                // Marcar como completada si no lo estaba
                $userLesson->completed_at = Carbon::now();
            } elseif (!$isCompleted && !is_null($userLesson->completed_at)) {
                // Desmarcar si ya estaba completada
                $userLesson->completed_at = null;
            }
        }
        
        // 3. Guardar todos los cambios
        $userLesson->save();

        return response()->json([
            'message' => 'Progreso actualizado exitosamente.',
            'progress' => $userLesson->load('lesson')
        ], 200);
    }
    

    /**
     * @OA\Delete(
     * path="/api/user-lessons/{user_lesson}",
     * tags={"Progreso de Lecciones"},
     * summary="Eliminar un registro de progreso",
     * description="Elimina el registro de progreso (UserLesson) especificado. Requiere que el registro pertenezca al usuario autenticado.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_lesson",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del registro de progreso (UserLesson) a eliminar"
     * ),
     * @OA\Response(
     * response=204,
     * description="Progreso eliminado exitosamente (No Content)"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acción no autorizada (el progreso no pertenece al usuario)"
     * )
     * )
     */
    public function destroy(UserLesson $userLesson): JsonResponse
    {
        // Política de autorización: solo el dueño puede eliminar su registro
        if ($userLesson->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.'); 
        }
        
        $userLesson->delete();

        return response()->json(null, 204);
    }
}
