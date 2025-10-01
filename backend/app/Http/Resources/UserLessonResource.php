<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para transformar la entidad UserLessonEntity (progreso del usuario en una lección).
 * Incluye la información expandida del usuario y de la lección asociada.
 */
class UserLessonResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // NOTA: Para que esto funcione, la UserLessonEntity debe tener relaciones definidas:
        // $this->user (apuntando a User) y $this->lesson (apuntando a Lesson/LeccionEntity).

        return [
            // 1. Campos primarios de la tabla pivote (UserLesson)
            'id' => $this->id, // Asumiendo que esta entidad tiene un ID propio

            // 2. Información sobre el estado de la lección
            'is_completed' => (bool) $this->completed_at,
            'completed_at' => $this->completed_at,

            // 3. Incluir la información completa del usuario (relación user_id_lesson)
            // Se usa el UserResource para estructurar la respuesta del Usuario.
            'user' => new UserResource($this->whenLoaded('user')),
            
            // 4. Incluir la información completa de la lección (relación lesson_id_lesson)
            // Se usa el LessonResource para estructurar la respuesta de la Lección.
            'lesson' => new LessonResource($this->whenLoaded('lesson')),

            // 5. Metadatos
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
