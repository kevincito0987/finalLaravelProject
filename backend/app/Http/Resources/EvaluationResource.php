<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LessonResource; 
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 * title="Evaluation Resource",
 * description="Define la estructura de una Evaluación, incluyendo la Lección completa si está cargada.",
 * @OA\Xml(name="EvaluationResource")
 * )
 */
class EvaluationResource extends JsonResource
{
    /**
     * Transforma la entidad de Evaluación en un array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     * @OA\Property(property="evaluationId", type="integer", description="ID único de la evaluación.", example=10)
     * @OA\Property(
     * property="lessonId",
     * type="integer",
     * description="ID de la lección a la que pertenece esta evaluación (FK).",
     * example=5
     * )
     * @OA\Property(
     * property="lessonData",
     * type="object",
     * description="Datos completos de la Lección (Lesson) asociada, o solo el ID si no se cargó.",
     * ref="#/components/schemas/LessonResource"
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            // Campos propios de la evaluación
            'evaluationId' => $this->evaluation_id,
            
            // ID de la lección (simple referencia)
            'lessonId' => $this->lesson_id_evaluation,

            // 2. Información de la Lección (Lesson) completa
            'lessonData' => $this->whenLoaded('lesson', function () {
                // SOLUCIÓN CLAVE: 
                // Si la Entidad espera 'lessonId' (camelCase) pero el Modelo Eloquent 
                // solo devuelve 'lesson_id' (snake_case) o la Entidad no lo mapea bien,
                // aseguramos que la propiedad exista antes de crear el LessonResource.

                // Clonamos el objeto de la lección cargada.
                $lesson = clone $this->lesson; 

                // Forzamos la asignación de la clave primaria en camelCase,
                // usando la clave primaria (PK) real del modelo cargado.
                // Asumimos que $this->lesson->lesson_id es accesible.
                $lesson->lessonId = $lesson->lesson_id;

                return new LessonResource($lesson);

            }, 
            // FALLBACK: Si la Lección no fue cargada, devolvemos un objeto simple 
            // con el ID (para mantener la consistencia).
            [
                'lessonId' => $this->lesson_id_evaluation, // Usamos la FK de la evaluación.
                'lessonName' => null, 
                'description' => null,
                'lessonType' => null,
            ]),
        ];
    }
}
