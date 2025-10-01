<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\EvaluationQuestionResource;
use App\Core\Interfaces\EvaluationQuestionRepositoryInterface;
use App\Http\Requests\StoreEvaluationQuestionRequest;
use App\Http\Requests\UpdateEvaluationQuestionRequest;
use Illuminate\Http\JsonResponse;
use App\Core\Entities\Evaluation\EvaluationQuestion; // Necesario para la autorización a nivel de Entidad/Clase
// Importaciones de Swagger
use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 * name="Evaluation Questions",
 * description="Gestión de las Preguntas de Evaluación asociadas a Cards y Evaluaciones."
 * )
 */
class EvaluationQuestionController extends Controller
{
    protected $evaluationQuestionRepository;

    /**
     * Inyecta el repositorio en el constructor.
     *
     * @param EvaluationQuestionRepositoryInterface $evaluationQuestionRepository
     */
    public function __construct(EvaluationQuestionRepositoryInterface $evaluationQuestionRepository)
    {
        $this->evaluationQuestionRepository = $evaluationQuestionRepository;

        // Opcional: Registrar la política si usas el método de políticas de Laravel
        // $this->authorizeResource(EvaluationQuestion::class, 'questionId'); 
    }

    /**
     * @OA\Get(
     * path="/api/evaluation-questions",
     * summary="Listar todas las preguntas de evaluación",
     * tags={"Evaluation Questions"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Lista de preguntas de evaluación",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/EvaluationQuestionResource")
     * )
     * )
     * )
     */
    public function index(): JsonResponse
    {
        // El acceso de lectura está protegido a nivel de ruta (Middleware 'role:user,therapist,admin')
        $questions = $this->evaluationQuestionRepository->all();
        return EvaluationQuestionResource::collection($questions)->response();
    }

    /**
     * @OA\Post(
     * path="/api/evaluation-questions",
     * summary="Crear una nueva pregunta de evaluación",
     * tags={"Evaluation Questions"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"evaluation_id_question", "card_id_evaluation", "question_text"},
     * @OA\Property(property="evaluation_id_question", type="integer", example=1, description="ID de la Evaluación a la que pertenece (FK)"),
     * @OA\Property(property="card_id_evaluation", type="integer", example=10, description="ID de la Card asociada (FK)"),
     * @OA\Property(property="question_text", type="string", example="¿Qué concepto se describe en esta tarjeta?"),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Pregunta creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationQuestionResource")
     * ),
     * @OA\Response(response=422, description="Error de validación"),
     * @OA\Response(response=403, description="No autorizado")
     * )
     */
    public function store(StoreEvaluationQuestionRequest $request): JsonResponse
    {
        // Solo 'therapist' y 'admin' tienen acceso a esta ruta. 
        // Si usas Policies: $this->authorize('create', EvaluationQuestion::class);
        
        $question = $this->evaluationQuestionRepository->create($request->validated());

        return (new EvaluationQuestionResource($question))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     * path="/api/evaluation-questions/{questionId}",
     * summary="Mostrar una pregunta de evaluación específica",
     * tags={"Evaluation Questions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="questionId",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la pregunta a mostrar"
     * ),
     * @OA\Response(
     * response=200,
     * description="Detalles de la pregunta",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationQuestionResource")
     * ),
     * @OA\Response(response=404, description="Pregunta no encontrada")
     * )
     */
    public function show(int $questionId): JsonResponse
    {
        // El acceso de lectura está protegido a nivel de ruta (Middleware 'role:user,therapist,admin')
        $question = $this->evaluationQuestionRepository->findById($questionId);
        
        if (!$question) {
            return response()->json(['message' => 'Pregunta de evaluación no encontrada.'], 404);
        }

        return (new EvaluationQuestionResource($question))->response();
    }

    /**
     * @OA\Put(
     * path="/api/evaluation-questions/{questionId}",
     * summary="Actualizar una pregunta de evaluación existente",
     * tags={"Evaluation Questions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="questionId",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la pregunta a actualizar"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="evaluation_id_question", type="integer", example=2, description="ID de la Evaluación (opcional)"),
     * @OA\Property(property="card_id_evaluation", type="integer", example=11, description="ID de la Card (opcional)"),
     * @OA\Property(property="question_text", type="string", example="¿Cuál es el concepto central? (actualizado)"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pregunta actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/EvaluationQuestionResource")
     * ),
     * @OA\Response(response=404, description="Pregunta no encontrada"),
     * @OA\Response(response=422, description="Error de validación"),
     * @OA\Response(response=403, description="No autorizado")
     * )
     */
    public function update(UpdateEvaluationQuestionRequest $request, int $questionId): JsonResponse
    {
        // Solo 'therapist' y 'admin' tienen acceso a esta ruta. 
        // Si usas Policies: $this->authorize('update', EvaluationQuestion::class);
        
        $question = $this->evaluationQuestionRepository->update($questionId, $request->validated());
        
        if (!$question) {
            return response()->json(['message' => 'Pregunta de evaluación no encontrada.'], 404);
        }

        return (new EvaluationQuestionResource($question))->response();
    }

    /**
     * @OA\Delete(
     * path="/api/evaluation-questions/{questionId}",
     * summary="Eliminar una pregunta de evaluación",
     * tags={"Evaluation Questions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="questionId",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la pregunta a eliminar"
     * ),
     * @OA\Response(
     * response=204,
     * description="Pregunta eliminada exitosamente"
     * ),
     * @OA\Response(response=404, description="Pregunta no encontrada"),
     * @OA\Response(response=403, description="No autorizado")
     * )
     */
    public function destroy(int $questionId): JsonResponse
    {
        // Solo 'therapist' y 'admin' tienen acceso a esta ruta. 
        // Si usas Policies: $this->authorize('delete', EvaluationQuestion::class);
        
        $deleted = $this->evaluationQuestionRepository->delete($questionId);
        
        if (!$deleted) {
             return response()->json(['message' => 'Pregunta de evaluación no encontrada.'], 404);
        }
        
        return response()->json(null, 204);
    }
}
