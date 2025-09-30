<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Http\Resources\EvaluationResource;
use App\Http\Requests\CreateEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 * name="Evaluations",
 * description="Gestión de las Evaluaciones de Lecciones"
 * )
 */
class EvaluationController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/evaluations",
     * operationId="getEvaluationsList",
     * tags={"Evaluations"},
     * summary="Obtiene la lista de todas las Evaluaciones",
     * description="Recupera un listado de todas las evaluaciones registradas, incluyendo la lección asociada.",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Lista de evaluaciones obtenida exitosamente",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/EvaluationResource")
     * )
     * )
     * )
     */
    public function index()
    {
        // Optamos por cargar la relación 'lesson' (y posiblemente 'results' si existe)
        $evaluations = Evaluation::with(['lesson'])->get();
        return EvaluationResource::collection($evaluations);
    }

    /**
     * @OA\Post(
     * path="/api/evaluations",
     * operationId="createEvaluation",
     * tags={"Evaluations"},
     * summary="Crea una nueva Evaluación",
     * description="Inicia una nueva sesión de evaluación, asociándola a una lección. El 'evaluationId' se autogenera.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la evaluación a crear (solo requiere ID de la lección).",
     * @OA\JsonContent(ref="#/components/schemas/CreateEvaluationRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Evaluación creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationResource")
     * ),
     * @OA\Response(response=422, description="Error de validación: El ID de la lección es inválido o faltante.")
     * )
     */
    public function store(CreateEvaluationRequest $request)
    {
        // El EvaluationId se autogenera al crear
        $evaluation = Evaluation::create($request->validated()); 

        // Cargar la relación 'lesson' para el Resource
        $evaluation->load('lesson');

        return new EvaluationResource($evaluation);
    }

    /**
     * @OA\Get(
     * path="/api/evaluations/{evaluationId}",
     * operationId="getEvaluationById",
     * tags={"Evaluations"},
     * summary="Obtiene una Evaluación específica por ID",
     * description="Muestra los detalles de una evaluación, incluyendo la lección asociada.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="evaluationId",
     * in="path",
     * required=true,
     * description="ID de la Evaluación a obtener",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Evaluación obtenida exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationResource")
     * ),
     * @OA\Response(response=404, description="Evaluación no encontrada")
     * )
     */
    public function show(Evaluation $evaluation)
    {
        // Usamos Model Binding. Cargamos la relación 'lesson'
        $evaluation->load('lesson');

        return new EvaluationResource($evaluation);
    }

    /**
     * @OA\Put(
     * path="/api/evaluations/{evaluationId}",
     * operationId="updateEvaluation",
     * tags={"Evaluations"},
     * summary="Actualiza una Evaluación existente",
     * description="Modifica la evaluación especificada por ID, permitiendo opcionalmente cambiar la lección asociada.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="evaluationId",
     * in="path",
     * required=true,
     * description="ID de la Evaluación a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Nuevos datos de la evaluación. Solo se puede actualizar el ID de la lección (opcional).",
     * @OA\JsonContent(ref="#/components/schemas/UpdateEvaluationRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Evaluación actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationResource")
     * ),
     * @OA\Response(response=404, description="Evaluación no encontrada"),
     * @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        // Solo actualiza si hay campos validados y presentes en el request
        $evaluation->update($request->validated());
        
        // Cargar la relación 'lesson' antes de devolver
        $evaluation->load('lesson'); 

        return new EvaluationResource($evaluation);
    }

    /**
     * @OA\Delete(
     * path="/api/evaluations/{evaluationId}",
     * operationId="deleteEvaluation",
     * tags={"Evaluations"},
     * summary="Elimina una Evaluación por ID",
     * description="Elimina la evaluación y sus referencias. Esto es un borrado permanente.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="evaluationId",
     * in="path",
     * required=true,
     * description="ID de la Evaluación a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=204, description="Evaluación eliminada exitosamente (No Content)"),
     * @OA\Response(response=404, description="Evaluación no encontrada")
     * )
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        // Respuesta 204 No Content (sin cuerpo)
        return response()->noContent();
    }
}
