<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * schema="CreateEvaluationRequest",
 * title="Create Evaluation Request",
 * description="Datos necesarios para registrar una nueva evaluación. El 'evaluationId' se genera automáticamente.",
 * required={"lesson_id_evaluation", "score"},
 * @OA\Xml(name="CreateEvaluationRequest"),
 * @OA\Property(
 * property="lesson_id_evaluation",
 * type="integer",
 * description="ID de la lección a la que corresponde la evaluación.",
 * example=5
 * ),
 * @OA\Property(
 * property="score",
 * type="number",
 * format="float",
 * description="Puntuación obtenida en la evaluación (0.0 a 100.0).",
 * example=95.5
 * )
 * )
 */
class CreateEvaluationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // Generalmente, si usas middleware de autenticación, puedes devolver true.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Se recomienda también validar el campo 'score' ya que está marcado como required en el Schema
        return [
            // CRÍTICO: Se cambia 'id' por 'lesson_id' en la regla exists.
            'lesson_id_evaluation' => ['required', 'integer', 'exists:lessons,lesson_id'], 
        ];
    }
}
