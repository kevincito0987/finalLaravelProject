<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CardResource;
use App\Http\Resources\LessonResource; 
// Importaciones de Swagger
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 * title="LessonCard Resource",
 * description="Define la estructura de la asociación LessonCard, incluyendo la tarjeta completa.",
 * @OA\Xml(name="LessonCardResource")
 * )
 */
class LessonCardResource extends JsonResource
{
    /**
     * Transforma la asociación LessonCard en una estructura de array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 1. Información de la asociación (tabla pivote)
        return [
            // Posición de la tarjeta dentro de la lección
            'orderInLesson' => $this->order_in_lesson,
            
            // ID de la lección (usando el nombre de campo real)
            'lessonId' => $this->lesson_id, // Usamos lesson_id

            // 2. Información de la Tarjeta (Card) completa
            // Usamos la relación 'card' (LessonCard::card())
            'cardData' => $this->whenLoaded('card', function () {
                // 'card' es el nombre de la relación que apunta al modelo Card
                return new CardResource($this->card);
            }, [
                // Fallback: Si la Card no fue cargada, solo devolvemos el ID.
                'cardId' => $this->card_id, // Usamos card_id
            ]),
        ];
    }
}
