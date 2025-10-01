<?php

namespace App\Http\Controllers;

// Importaciones de Clases de Dominio y Aplicación
use App\Core\Services\UserProgressService;
use App\Http\Requests\StoreUserProgressRequest;
use App\Http\Requests\UpdateUserProgressRequest;
use App\Http\Resources\UserProgressResource; 
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth; // Necesario para obtener el usuario autenticado
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller para gestionar el progreso del usuario.
 * Proporciona endpoints para registrar (POST), actualizar (PUT), obtener (GET) y eliminar (DELETE) el score de una tarjeta.
 */
class UserProgressController extends Controller
{
    private UserProgressService $userProgressService;

    /**
     * Inyección del servicio de dominio a través del constructor.
     */
    public function __construct(UserProgressService $userProgressService)
    {
        $this->userProgressService = $userProgressService;
    }

    /**
     * Devuelve una lista de todos los registros de progreso del usuario autenticado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // 1. Obtener el ID del usuario autenticado.
        $userId = Auth::id();

        // 2. Llamar al servicio para obtener todos los progresos de ese usuario.
        // Asumimos que quieres filtrar por el usuario autenticado para una aplicación real.
        $progressEntities = $this->userProgressService->getAllUserProgress($userId);

        // 3. Devolver la colección usando el Resource Collection.
        // NOTA: UserProgressResource::collection() es lo que convierte el array de Entidades a una respuesta JSON.
        return UserProgressResource::collection($progressEntities)->response();
    }


    /**
     * Maneja el registro inicial o la actualización de una nueva entrada de progreso (POST).
     *
     * @param StoreUserProgressRequest $request La solicitud validada para almacenar.
     * @return JsonResponse
     */
    public function store(StoreUserProgressRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // 1. Llamar al servicio, usando el orden de parámetros (userId, lessonId, cardId, newMasteryLevel/score).
        $progressEntity = $this->userProgressService->registerCardProgress(
            $validated['user_id'],
            $validated['lesson_id'],
            $validated['card_id'],
            $validated['score']
        );

        // 2. Devolver la respuesta 201 Created.
        return (new UserProgressResource($progressEntity))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Maneja la actualización explícita del progreso existente (PUT).
     *
     * @param UpdateUserProgressRequest $request La solicitud validada para actualizar.
     * @return JsonResponse
     */
    public function update(UpdateUserProgressRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // 1. Llamar al mismo método de servicio, usando el orden de parámetros.
        $progressEntity = $this->userProgressService->registerCardProgress(
            $validated['user_id'],
            $validated['lesson_id'],
            $validated['card_id'],
            $validated['score']
        );

        // 2. Devolver la respuesta 200 OK.
        return (new UserProgressResource($progressEntity))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Obtiene el progreso actual de una tarjeta específica usando las claves compuestas.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $cardId
     * @return JsonResponse
     */
    public function show(int $userId, int $lessonId, int $cardId): JsonResponse
    {
        $progressEntity = $this->userProgressService->getCurrentCardProgress(
            userId: $userId,
            lessonId: $lessonId,
            cardId: $cardId
        );

        if (!$progressEntity) {
            return response()->json([
                'message' => 'Progreso no encontrado para la tarjeta especificada.',
            ], Response::HTTP_NOT_FOUND);
        }

        // 2. Llamar a ->response() en el Resource para que devuelva un JsonResponse.
        return (new UserProgressResource($progressEntity))->response();
    }

    /**
     * Elimina un registro de progreso por sus claves compuestas.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $cardId
     * @return JsonResponse
     */
    public function destroy(int $userId, int $lessonId, int $cardId): JsonResponse
    {
        // 1. Buscar la entidad por sus claves.
        $progressEntity = $this->userProgressService->getCurrentCardProgress(
            userId: $userId,
            lessonId: $lessonId,
            cardId: $cardId
        );

        if (!$progressEntity) {
            // Si no existe, devuelve 204 No Content.
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        // 2. Delegar la eliminación al servicio.
        if (!$this->userProgressService->deleteProgress($progressEntity)) {
             return response()->json(['message' => 'No se pudo eliminar el progreso.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // 3. Devolver 204 No Content.
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
