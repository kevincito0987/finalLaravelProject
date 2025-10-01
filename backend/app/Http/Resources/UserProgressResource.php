<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// Importa los Resources que se usarán dentro de este
use App\Http\Resources\UserResource;
use App\Http\Resources\CardResource;
use App\Models\UserProgress;

class UserProgressResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 1. Determinar si la relación anidada a la Card real existe y fue cargada.
        // Ahora usamos $this->card->card_sesion para reflejar el nombre de tu relación.
        $realCard = $this->whenLoaded('card', function (UserProgress $progress) {
            // $progress->card es el modelo LessonCard
            // $progress->card->card_sesion es el modelo Card real
            return $progress->card->card_sesion ?? null;
        });

        return [
            // 2. Campos primarios y de progreso
            'progress_id' => $this->progress_id, 
            'use_count' => $this->use_count,
            'last_used_at' => $this->last_used_at,

            // 3. Relación con el USUARIO
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }, $this->user_id_progress),

            // 4. Relación con la FICHA/CARD (LessonCard -> Card)
            'card' => $this->whenLoaded('card', function () use ($realCard) {
                // Solo serializa con CardResource si $realCard tiene contenido.
                if ($realCard) {
                    return new CardResource($realCard);
                }
                
                // Fallback si la relación anidada no se cargó o es nula
                return $this->card_id_progress; 
                
            }, $this->card_id_progress), 

            // 5. Incluir el ID de LessonCard 
            'lesson_card_id' => $this->whenLoaded('card', function () {
                return $this->card->id ?? null;
            }),
        ];
    }
}
