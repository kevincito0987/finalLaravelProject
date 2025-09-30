<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * schema="UpdateEvaluationRequest",
 * title="Update Evaluation Request",
 * description="Datos para modificar una evaluación existente. El 'evaluationId' se toma de la URL.",
 * @OA\Xml(name="UpdateEvaluationRequest"),
 * @OA\Property(
 * property="lesson_id_evaluation",
 * type="integer",
 * description="ID de la lección (opcional si se mantiene el enlace).",
 * example=5
 * ),
 * @OA\Property(
 * property="score",
 * type="number",
 * format="float",
 * description="Nueva puntuación obtenida en la evaluación (0.0 a 100.0).",
 * example=98.0
 * )
 * )
 */
class UpdateEvaluationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // REGLA CORREGIDA: Se debe especificar la columna de clave primaria correcta.
            // La validación busca el valor de 'lesson_id_evaluation' en la tabla 'lessons',
            // en la columna 'lesson_id' (que es la PK de la tabla lessons).
            'lesson_id_evaluation' => ['sometimes', 'integer', 'exists:lessons,lesson_id'],
        ];
    }
}
