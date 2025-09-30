<?php

namespace App\Http\Resources;

use App\Core\Entities\LessonEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LessonEntity
 * * @OA\Schema(
 * schema="LessonResource",
 * title="Lesson Resource",
 * description="Estructura de la respuesta para una lección.",
 * @OA\Property(
 * property="lessonId",
 * type="integer",
 * example=1,
 * description="Identificador único de la lección."
 * ),
 * @OA\Property(
 * property="lessonName",
 * type="string",
 * example="Introducción a la Terapia Cognitivo-Conductual",
 * description="Nombre de la lección."
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * example="Fundamentos y técnicas básicas de la TCC.",
 * description="Descripción detallada de la lección."
 * ),
 * @OA\Property(
 * property="lessonType",
 * type="string",
 * example="video",
 * description="Tipo de contenido de la lección (e.g., 'video', 'lectura')."
 * )
 * )
 */
class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // El constructor del JsonResource espera un objeto que tenga propiedades.
        // Asumimos que $this.resource es una instancia de LessonEntity.
        
        // Mapeamos los campos de la entidad (camelCase) a la estructura JSON deseada.
        return [
            'lessonId' => $this->lessonId,
            'lessonName' => $this->lessonName,
            'description' => $this->description,
            'lessonType' => $this->lessonType,
        ];
    }
}
