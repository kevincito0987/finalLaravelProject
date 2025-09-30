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
     * * @OA\Property(
     * property="orderInLesson",
     * type="integer",
     * description="Posición ordinal de la tarjeta dentro de la lección.",
     * example=1
     * )
     * @OA\Property(
     * property="lessonId",
     * type="integer",
     * description="ID de la lección a la que pertenece esta asociación.",
     * example=5
     * )
     * @OA\Property(
     * property="cardData",
     * type="object",
     * description="Datos completos de la tarjeta (Card) asociada a esta posición.",
     * ref="#/components/schemas/CardResource"
     * )
     */
    public function toArray(Request $request): array
    {
        // 1. Información de la asociación (tabla pivote)
        return [
            // Posición de la tarjeta dentro de la lección
            'orderInLesson' => $this->order_in_lesson,
            
            // ID de la lección a la que pertenece (mapeando de lesson_id_sesion)
            'lessonId' => $this->lesson_id_sesion,

            // 2. Información de la Tarjeta (Card) completa
            // Usamos CardResource para formatear la Card de acuerdo a tu esquema
            'cardData' => $this->whenLoaded('card_sesion', function () {
                // 'card_sesion' es el nombre de la relación que apunta al modelo Card
                // Pasamos la instancia relacionada al CardResource
                return new CardResource($this->card_sesion);
            }, [
                // Fallback: Si la Card no fue cargada, solo devolvemos el ID.
                'cardId' => $this->card_id_sesion,
            ]),
        ];
    }
}
