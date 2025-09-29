<?php

namespace App\Http\Controllers;

use App\Core\Services\CommunicationMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 * name="Communication Methods",
 * description="Endpoints para la gestión de métodos de comunicación (PECS, Lenguaje de Señas, etc.)"
 * )
 */
class CommunicationMethodController extends Controller
{
    protected CommunicationMethodService $service;

    public function __construct(CommunicationMethodService $service)
    {
        // Inyección de dependencia del servicio de negocio (DI)
        $this->service = $service;
    }

    /**
     * @OA\Get(
     * path="/communication-methods",
     * tags={"Communication Methods"},
     * summary="Listar todos los métodos de comunicación",
     * description="Devuelve una lista de todos los métodos de comunicación disponibles.",
     * @OA\Response(
     * response=200,
     * description="Lista de métodos obtenida con éxito.",
     * @OA\JsonContent(
     * @OA\Property(property="data", type="array",
     * @OA\Items(
     * @OA\Property(property="method_id", type="integer", example=1),
     * @OA\Property(property="method_name", type="string", example="Tarjetas PECS")
     * )
     * )
     * )
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $methods = $this->service->getAll()->map(fn($m) => $m->toArray());
        
        return response()->json([
            'data' => $methods
        ]);
    }

    /**
     * @OA\Get(
     * path="/communication-methods/{methodId}",
     * tags={"Communication Methods"},
     * summary="Obtener un método por ID",
     * description="Devuelve la información de un método de comunicación específico.",
     * @OA\Parameter(
     * name="methodId",
     * in="path",
     * description="ID del método de comunicación a obtener.",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Método obtenido con éxito.",
     * @OA\JsonContent(
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="method_id", type="integer", example=1),
     * @OA\Property(property="method_name", type="string", example="Tarjetas PECS")
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Método no encontrado.")
     * )
     */
    public function show(int $methodId): JsonResponse
    {
        try {
            $method = $this->service->getById($methodId);
            
            return response()->json([
                'data' => $method->toArray()
            ]);
            
        } catch (\Exception $e) {
            // Captura la excepción de no encontrado del Service
            return response()->json(['message' => 'Método de comunicación no encontrado.'], 404);
        }
    }

    /**
     * @OA\Post(
     * path="/communication-methods",
     * tags={"Communication Methods"},
     * summary="Crear un nuevo método",
     * description="Crea un nuevo método de comunicación con un nombre único.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"method_name"},
     * @OA\Property(property="method_name", type="string", example="Comunicación Aumentativa")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Método creado exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Método creado exitosamente."),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="method_id", type="integer", example=2),
     * @OA\Property(property="method_name", type="string", example="Comunicación Aumentativa")
     * )
     * )
     * ),
     * @OA\Response(response=422, description="Error de validación (nombre de método duplicado o faltante).")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // Validación manual, idealmente con FormRequest
        $validator = Validator::make($request->all(), [
            'method_name' => 'required|string|max:100|unique:communication_methods,method_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $method = $this->service->create($request->input('method_name'));
            
            return response()->json([
                'message' => 'Método creado exitosamente.',
                'data' => $method->toArray()
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el método.'], 500);
        }
    }

    /**
     * @OA\Put(
     * path="/communication-methods/{methodId}",
     * tags={"Communication Methods"},
     * summary="Actualizar un método",
     * description="Actualiza el nombre de un método de comunicación existente.",
     * @OA\Parameter(
     * name="methodId",
     * in="path",
     * description="ID del método de comunicación a actualizar.",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"method_name"},
     * @OA\Property(property="method_name", type="string", example="Señas Colombianas")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Método actualizado exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Método actualizado exitosamente."),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="method_id", type="integer", example=1),
     * @OA\Property(property="method_name", type="string", example="Señas Colombianas")
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Método no encontrado."),
     * @OA\Response(response=422, description="Error de validación (nombre de método duplicado o faltante).")
     * )
     */
    public function update(Request $request, int $methodId): JsonResponse
    {
        // Validación de unicidad de 'method_name', ignorando el ID actual
        $validator = Validator::make($request->all(), [
            'method_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('communication_methods', 'method_name')->ignore($methodId, 'method_id'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $method = $this->service->update($methodId, $request->input('method_name'));
            
            return response()->json([
                'message' => 'Método actualizado exitosamente.',
                'data' => $method->toArray()
            ]);
            
        } catch (\Exception $e) {
            // Captura la excepción de no encontrado del Service/Repository
            return response()->json(['message' => 'Método de comunicación no encontrado o error de actualización.'], 404);
        }
    }

    /**
     * @OA\Delete(
     * path="/communication-methods/{methodId}",
     * tags={"Communication Methods"},
     * summary="Eliminar un método",
     * description="Elimina permanentemente un método de comunicación por su ID.",
     * @OA\Parameter(
     * name="methodId",
     * in="path",
     * description="ID del método de comunicación a eliminar.",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(response=204, description="Método eliminado con éxito (No Content)."),
     * @OA\Response(response=404, description="Método no encontrado.")
     * )
     */
    public function destroy(int $methodId): JsonResponse
    {
        if ($this->service->delete($methodId)) {
            // Respuesta exitosa sin contenido (204 No Content)
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Método de comunicación no encontrado o no se pudo eliminar.'], 404);
    }
}
