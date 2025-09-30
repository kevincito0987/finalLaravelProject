<?php

namespace App\Http\Controllers;

use App\Models\LessonCard; // Asume que el modelo se llama LessonCard
use App\Http\Requests\StoreLessonCardRequest;
use App\Http\Requests\UpdateLessonCardRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA; // Importar la clase de anotaciones

/**
 * @OA\Tag(
 * name="LessonCard",
 * description="Gestión de la asociación de tarjetas a lecciones (LessonCard - Tabla pivote)."
 * )
 * * Controlador para gestionar las asociaciones entre lecciones y tarjetas (LessonCard).
 * Esta tabla pivote maneja el orden de las tarjetas dentro de una lección específica.
 */
class LessonCardController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/lesson-cards",
     * operationId="getLessonCardsList",
     * tags={"LessonCard"},
     * summary="Obtiene la lista de todas las asociaciones LessonCard",
     * description="Muestra una lista de todas las asociaciones, opcionalmente filtrada por ID de lección.",
     * @OA\Parameter(
     * name="lesson_id_sesion",
     * in="query",
     * description="Filtra las asociaciones por el ID de la lección.",
     * required=false,
     * @OA\Schema(type="integer", example=101)
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de LessonCard obtenida exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Lista de asociaciones LessonCard obtenida exitosamente."),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/LessonCard"))
     * )
     * ),
     * @OA\Response(response=500, description="Error interno del servidor")
     * )
     *
     * Muestra una lista de todas las asociaciones LessonCard (opcionalmente filtrada).
     *
     * @param Request $request La solicitud HTTP.
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Se puede filtrar por lesson_id_sesion si es necesario, 
        // pero por defecto listamos todas las asociaciones con su lección y tarjeta.
        $query = LessonCard::query()
            ->with(['lesson', 'card']) // Carga las relaciones para mostrar más detalles
            ->orderBy('order_in_lesson', 'asc');

        if ($request->has('lesson_id_sesion')) {
            $query->where('lesson_id', $request->input('lesson_id_sesion'));
        }

        $lessonCards = $query->get();

        return response()->json([
            'message' => 'Lista de asociaciones LessonCard obtenida exitosamente.',
            'data' => $lessonCards
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/lesson-cards",
     * operationId="storeLessonCard",
     * tags={"LessonCard"},
     * summary="Crea una nueva asociación LessonCard",
     * description="Asocia una Card existente a una Lesson existente y establece su orden.",
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la asociación a crear (ID de lección, ID de tarjeta y orden)",
     * @OA\JsonContent(ref="#/components/schemas/StoreLessonCardRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Asociación LessonCard creada exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Asociación LessonCard creada exitosamente."),
     * @OA\Property(property="data", ref="#/components/schemas/LessonCard")
     * )
     * ),
     * @OA\Response(response=422, description="Error de validación"),
     * @OA\Response(response=500, description="Error al crear la asociación")
     * )
     * * Almacena una nueva asociación LessonCard en la base de datos (Método POST).
     *
     * @param StoreLessonCardRequest $request El request validado con los IDs y el orden.
     * @return JsonResponse
     */
    public function store(StoreLessonCardRequest $request): JsonResponse
    {
        try {
            // Obtener los datos validados
            $validatedData = $request->validated();
            
            // CRÍTICO: Mapeo de los campos del Request (ej: 'lesson_id_sesion') 
            // a los nombres de columna reales de la tabla pivote ('lesson_id').
            $lessonCard = LessonCard::create([
                // Columna en DB         => Campo del Request validado
                'lesson_id'       => $validatedData['lesson_id_sesion'], 
                'card_id'         => $validatedData['card_id_sesion'],     
                'order_in_lesson' => $validatedData['order_in_lesson'],
            ]);

            // Cargar relaciones si es necesario para la respuesta
            // $lessonCard->load(['lesson', 'card']); 

            return response()->json([
                'message' => 'Asociación LessonCard creada exitosamente.',
                'data' => $lessonCard
            ], 201); // 201 Created
        } catch (\Exception $e) {
            // Logear el error completo para propósitos de depuración
            Log::error("Error al crear la asociación LessonCard: " . $e->getMessage());
            
            // Retornar una respuesta de error legible para el usuario de la API
            return response()->json([
                'message' => 'Error al crear la asociación LessonCard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/lesson-cards/{lessonId}",
     * operationId="getLessonCardsByLesson",
     * tags={"LessonCard"},
     * summary="Obtiene todas las tarjetas para una lección específica",
     * description="Recupera todas las asociaciones LessonCard ordenadas por 'order_in_lesson' para el lessonId dado.",
     * @OA\Parameter(
     * name="lessonId",
     * in="path",
     * required=true,
     * description="ID de la Lección (Lesson) cuyas tarjetas se quieren recuperar.",
     * @OA\Schema(type="integer", example=101)
     * ),
     * @OA\Response(
     * response=200,
     * description="Asociaciones recuperadas exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Asociaciones LessonCard recuperadas exitosamente."),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/LessonCard"))
     * )
     * ),
     * @OA\Response(response=404, description="No se encontraron asociaciones para la lección."),
     * @OA\Response(response=500, description="Error interno del servidor")
     * )
     *
     * Muestra una asociación LessonCard específica.
     *
     * @param int $lessonId El ID de la lección para buscar sus tarjetas.
     * @return JsonResponse
     */
    public function show(int $lessonId): JsonResponse
    {
        try {
            // Usamos where('lesson_id', ...) para obtener todas las tarjetas de la lección.
            // Y 'with' para cargar las relaciones 'lesson' y 'card'
            $lessonCards = LessonCard::where('lesson_id', $lessonId)
                                     ->with(['lesson', 'card']) // ¡Cargamos las relaciones aquí!
                                     ->orderBy('order_in_lesson', 'asc')
                                     ->get();

            if ($lessonCards->isEmpty()) {
                return response()->json([
                    'message' => "No se encontraron asociaciones LessonCard para la lección ID: {$lessonId}."
                ], 404);
            }

            return response()->json([
                'message' => 'Asociaciones LessonCard recuperadas exitosamente.',
                'data' => $lessonCards
            ]);
        } catch (\Exception $e) {
            Log::error("Error al obtener las LessonCards para la lección {$lessonId}: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno en el servidor',
                'errors' => [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/lesson-cards/{lesson_id}/{card_id}",
     * operationId="updateLessonCardOrder",
     * tags={"LessonCard"},
     * summary="Actualiza el orden de una asociación LessonCard",
     * description="Actualiza el campo 'order_in_lesson' para una LessonCard específica, identificada por sus IDs compuestos.",
     * @OA\Parameter(
     * name="lesson_id",
     * in="path",
     * required=true,
     * description="ID de la Lección.",
     * @OA\Schema(type="integer", example=101)
     * ),
     * @OA\Parameter(
     * name="card_id",
     * in="path",
     * required=true,
     * description="ID de la Tarjeta.",
     * @OA\Schema(type="integer", example=205)
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Nuevo orden para la tarjeta.",
     * @OA\JsonContent(ref="#/components/schemas/UpdateLessonCardRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Orden de LessonCard actualizado exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="LessonCard actualizada exitosamente."),
     * @OA\Property(property="data", ref="#/components/schemas/LessonCard")
     * )
     * ),
     * @OA\Response(response=404, description="Asociación LessonCard no encontrada."),
     * @OA\Response(response=422, description="Error de validación")
     * )
     *
     * Actualiza el orden de una asociación LessonCard existente (Método PUT/PATCH).
     * Solo requiere 'order_in_lesson'.
     *
     * @param UpdateLessonCardRequest $request El request validado con el nuevo orden.
     * @param int $lesson_id El ID de la Lección.
     * @param int $card_id El ID de la Tarjeta.
     * @return JsonResponse
     */
    public function update(UpdateLessonCardRequest $request, int $lesson_id, int $card_id): JsonResponse
    {
        $validatedData = $request->validated();

        // 1. Buscar el registro manualmente
        $lessonCard = LessonCard::where('lesson_id', $lesson_id)
                               ->where('card_id', $card_id)
                               ->first();

        // 2. Manejar el error 404
        if (!$lessonCard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asociación LessonCard no encontrada.',
            ], 404);
        }

        // 3. Actualizar el registro
        // Solo actualizamos 'order_in_lesson' ya que lesson_id y card_id son inmutables
        $lessonCard->update($validatedData);

        // 4. Cargar relaciones y retornar
        $lessonCard->load(['lesson', 'card']); 

        return response()->json([
            'message' => 'LessonCard actualizada exitosamente.',
            'data' => $lessonCard
        ], 200);
    }


    /**
     * @OA\Delete(
     * path="/api/lesson-cards/{id}",
     * operationId="deleteLessonCard",
     * tags={"LessonCard"},
     * summary="Elimina una asociación LessonCard",
     * description="Elimina una LessonCard específica por su clave primaria (ID).",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID primario del registro LessonCard (no los IDs compuestos).",
     * @OA\Schema(type="integer", example=45)
     * ),
     * @OA\Response(
     * response=204,
     * description="Asociación LessonCard eliminada exitosamente (No Content)."
     * ),
     * @OA\Response(response=404, description="Asociación LessonCard no encontrada."),
     * @OA\Response(response=500, description="Error al eliminar la asociación")
     * )
     *
     * Elimina una asociación LessonCard de la base de datos (Método DELETE).
     *
     * @param int $id El ID del registro LessonCard a eliminar.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $lessonCard = LessonCard::find($id);

        if (!$lessonCard) {
            return response()->json(['message' => 'Asociación LessonCard no encontrada.'], 404);
        }

        try {
            $lessonCard->delete();

            return response()->json([
                'message' => 'Asociación LessonCard eliminada exitosamente.'
            ], 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la asociación LessonCard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
