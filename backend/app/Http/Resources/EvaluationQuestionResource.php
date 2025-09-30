<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Core\Entities\EvaluationQuestion; 
// Si usas otros recursos para las relaciones, asegúrate de que existan o coméntalos
// use App\Http\Resources\EvaluationResource; 
// use App\Http\Resources\LessonCardResource; 

/**
 * @OA\Schema(
 * schema="EvaluationQuestionResource",
 * title="Evaluation Question Resource",
 * description="Estructura de la respuesta para una Pregunta de Evaluación",
 * @OA\Property(property="questionId", type="integer", example=1),
 * @OA\Property(property="evaluationId", type="integer", example=1),
 * @OA\Property(property="cardId", type="integer", example=5),
 * @OA\Property(property="questionText", type="string", example="¿Cuál es la respuesta correcta?"),
 * @OA\Property(property="correctAnswer", type="string", example="Opción A"),
 * @OA\Property(property="options", type="array", @OA\Items(type="string"), example={"Opción A", "Opción B", "Opción C"})
 * )
 */
class EvaluationQuestionResource extends JsonResource
{
    /**
     * Transforma la entidad o modelo en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // NOTA IMPORTANTE: Si $this es una Entidad de Dominio (no un Modelo de Eloquent),
        // los métodos de relación de Laravel como whenLoaded() no funcionarán.
        // Solo incluimos las propiedades directas de la Entidad (que ya corregimos a Camel Case).
        return [
            'questionId' => $this->questionId, 
            'evaluationId' => $this->evaluationId, 
            'cardId' => $this->cardId,
            'questionText' => $this->questionText,
            'correctAnswer' => $this->correctAnswer,
            'options' => $this->options,
            
            // Si necesitas incluir la relación, tendrías que verificar si la propiedad existe
            // y no es nula, ya que no puedes usar whenLoaded() en la Entidad.
            /*
            'evaluation' => isset($this->evaluation) ? new EvaluationResource($this->evaluation) : null,
            'card' => isset($this->card) ? new LessonCardResource($this->card) : null,
            */
        ];
    }
}
