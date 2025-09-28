<?php

namespace App\Http\Controllers;

use App\Core\Services\Communication\ManageCommunicationMethodsService;
use App\Http\Requests\CommunicationMethodRequest;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 * name="Communication Methods",
 * description="Gestión de los tipos de interacción (visual, auditivo, táctil)"
 * )
 */
class CommunicationMethodController extends Controller
{
    use ApiResponse;

    public function __construct(
        // Inyectar el Servicio de Aplicación del Core
        private readonly ManageCommunicationMethodsService $service
    ) {}

    // ----------------------------------------------------------------------
    // INDEX (GET ALL)
    // ----------------------------------------------------------------------
    /**
     * @OA\Get(
     * path="/api/communication-methods",
     * tags={"Communication Methods"},
     * summary="Obtiene la lista de todos los métodos de comunicación",
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="OK", 
     * @OA\JsonContent(type="array",
     * @OA\Items(type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="visual")
     * )
     * )
     * )
     * )
     */
    public function index()
    {
        // Llamar al servicio del Core para obtener las entidades
        $methods = $this->service->getMethods();

        // Mapear de la Entidad al formato de respuesta JSON (adaptación para la capa de presentación)
        $data = $methods->map(fn ($entity) => [
            'id' => $entity->id,
            'name' => $entity->name,
        ]);
        
        return $this->success($data);
    }

    // ----------------------------------------------------------------------
    // STORE (CREATE)
    // ----------------------------------------------------------------------
    /**
     * @OA\Post(
     * path="/api/communication-methods",
     * tags={"Communication Methods"},
     * summary="Crea un nuevo método de comunicación",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"method_name"},
     * @OA\Property(property="method_name", type="string", example="auditivo")
     * )
     * ),
     * @OA\Response(response=201, description="Creado", 
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="name", type="string", example="auditivo")
     * )
     * ),
     * @OA\Response(response=422, description="Error de Validación (ej. nombre duplicado)")
     * )
     */
    public function store(CommunicationMethodRequest $request)
    {
        $name = $request->validated('method_name');
        
        // Llamar al servicio del Core para ejecutar la lógica de creación
        $entity = $this->service->createMethod($name);

        return $this->success([
            'id' => $entity->id,
            'name' => $entity->name,
        ], 'Método creado correctamente', 201);
    }
    
    // ----------------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------------
    /**
     * @OA\Put(
     * path="/api/communication-methods/{id}",
     * tags={"Communication Methods"},
     * summary="Actualiza un método de comunicación existente",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, description="ID del método", @OA\Schema(type="integer", example=1)),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"method_name"},
     * @OA\Property(property="method_name", type="string", example="tactil")
     * )
     * ),
     * @OA\Response(response=200, description="Actualizado", 
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="tactil")
     * )
     * ),
     * @OA\Response(response=404, description="Método no encontrado"),
     * @OA\Response(response=422, description="Error de Validación")
     * )
     */
    public function update(CommunicationMethodRequest $request, int $id)
    {
        $name = $request->validated('method_name');

        // Llamar al servicio del Core para ejecutar la lógica de actualización
        $entity = $this->service->updateMethod($id, $name);

        if (!$entity) {
            return $this->error('Método de comunicación no encontrado', 404);
        }

        return $this->success([
            'id' => $entity->id,
            'name' => $entity->name,
        ], 'Método actualizado correctamente');
    }

    // ----------------------------------------------------------------------
    // DESTROY (DELETE)
    // ----------------------------------------------------------------------
    /**
     * @OA\Delete(
     * path="/api/communication-methods/{id}",
     * tags={"Communication Methods"},
     * summary="Elimina un método de comunicación",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, description="ID del método", @OA\Schema(type="integer", example=1)),
     * @OA\Response(response=200, description="Eliminado", 
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Método eliminado correctamente")
     * )
     * ),
     * @OA\Response(response=404, description="Método no encontrado")
     * )
     */
    public function destroy(int $id)
    {
        // Llamar al servicio del Core para ejecutar la lógica de eliminación
        $success = $this->service->deleteMethod($id);

        if (!$success) {
            return $this->error('Método de comunicación no encontrado', 404);
        }

        return $this->success(null, 'Método eliminado correctamente');
    }
}
