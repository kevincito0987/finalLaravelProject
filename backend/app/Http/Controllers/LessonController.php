<?php

namespace App\Http\Controllers;

use App\Core\Services\LessonService;
use App\Http\Requests\StoreLessonRequest; 
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\LessonResource; 
use App\Models\Lesson;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Tag(
 * name="Lessons",
 * description="Endpoints para la gestión de lecciones. (Acceso: user, admin, therapist)"
 * )
 */

class LessonController extends Controller
{
    use AuthorizesRequests;
    protected LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * @OA\Get(
     * path="/lessons",
     * tags={"Lessons"},
     * summary="Listar todas las lecciones",
     * description="Devuelve una lista paginada de todas las lecciones. Acceso: user, admin, therapist.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * required=false,
     * @OA\Schema(type="integer", example=15)
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de lecciones obtenida con éxito.",
     * @OA\JsonContent(type="object", 
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/LessonResource")),
     * @OA\Property(property="meta", type="object")
     * )
     * )
     * )
     */
    public function index(Request $request): ResourceCollection
    {
        // Autorización: Verifica viewAny (Permitido para todos los roles)
        $this->authorize('viewAny', Lesson::class); 

        $perPage = $request->query('per_page', 15);
        $lessons = $this->lessonService->getAllLessons($perPage);

        return LessonResource::collection($lessons);
    }

    /**
     * @OA\Post(
     * path="/lessons",
     * tags={"Lessons"},
     * summary="Crear una nueva lección",
     * description="Crea una nueva lección. Acceso: admin, therapist.",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"lessonName", "description", "lessonType"},
     * @OA\Property(property="lessonName", type="string", example="Introducción a la Terapia"),
     * @OA\Property(property="description", type="string", example="Lección básica sobre conceptos fundamentales."),
     * @OA\Property(property="lessonType", type="string", example="video")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Lección creada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/LessonResource")
     * ),
     * @OA\Response(response=403, description="No autorizado. Solo 'admin' o 'therapist'."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function store(StoreLessonRequest $request)
    {
        // Llama al nuevo método 'toEntity()' del Request para obtener la Entidad
        $lessonEntity = $request->toEntity();

        // Llama al servicio para crear la lección
        $createdLesson = $this->lessonService->createLesson($lessonEntity);

        // Retornar la respuesta (usando un Resource si lo tienes)
        return response()->json([
            'status' => 'success',
            'data' => $createdLesson, // Esto puede ser LessonResource::make($createdLesson)
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/lessons/{id}",
     * tags={"Lessons"},
     * summary="Obtener una lección por ID",
     * description="Devuelve la información de una lección específica. Acceso: user, admin, therapist.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="lesson",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Lección obtenida con éxito.",
     * @OA\JsonContent(ref="#/components/schemas/LessonResource")
     * ),
     * @OA\Response(response=404, description="Lección no encontrada.")
     * )
     */
    public function show(Lesson $lesson): JsonResponse
    {
        // Autorización: Verifica view (Permitido para todos los roles)
        $this->authorize('view', $lesson); 
        
        try {
            // Utilizamos el ID del modelo Eloquent para buscar la entidad
            $entity = $this->lessonService->getLesson($lesson->lesson_id);
            return (new LessonResource($entity))->response();
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lección no encontrada.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     * path="/lessons/{lesson}",
     * tags={"Lessons"},
     * summary="Actualizar una lección",
     * description="Actualiza una lección existente. Acceso: admin, therapist. Los campos son opcionales.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="lesson",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Solo se deben enviar los campos que se desean actualizar.",
     * @OA\JsonContent(
     * @OA\Property(property="lessonName", type="string", example="Conceptos Avanzados de Terapia", description="Opcional. Máximo 100 caracteres."),
     * @OA\Property(property="description", type="string", example="Actualización de la lección básica.", description="Opcional."),
     * @OA\Property(property="lessonType", type="string", example="lectura", description="Opcional. Máximo 50 caracteres.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Lección actualizada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/LessonResource")
     * ),
     * @OA\Response(response=403, description="No autorizado. Solo 'admin' o 'therapist'."),
     * @OA\Response(response=404, description="Lección no encontrada."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson) // Cambiado a inyección de modelo
    {
        // Autorización: Verifica update
        $this->authorize('update', $lesson);
        
        // Llama al método 'toEntity()' del Request para obtener los datos de la Entidad
        $lessonEntity = $request->toEntity();

        // Llama al servicio, pasando el ID de la lección del modelo inyectado
        $updatedLesson = $this->lessonService->updateLesson($lesson->lesson_id, $lessonEntity);

        // Retornar la respuesta
        return response()->json([
            'status' => 'success',
            'data' => $updatedLesson, // O LessonResource::make($updatedLesson)
        ], 200);
    }

    /**
     * @OA\Delete(
     * path="/lessons/{lesson}",
     * tags={"Lessons"},
     * summary="Eliminar una lección",
     * description="Elimina una lección por su ID. Acceso: admin, therapist.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="lesson",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(response=204, description="Lección eliminada con éxito."),
     * @OA\Response(response=403, description="No autorizado. Solo 'admin' o 'therapist'."),
     * @OA\Response(response=404, description="Lección no encontrada.")
     * )
     */
    public function destroy(Lesson $lesson): JsonResponse
    {
        // Autorización: Verifica delete (Permitido solo para 'admin' y 'therapist')
        $this->authorize('delete', $lesson);

        try {
            $this->lessonService->deleteLesson($lesson->lesson_id);

            // 204 No Content
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
            
        } catch (ModelNotFoundException $e) {
             return response()->json(['message' => 'Lección no encontrada para eliminar.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
