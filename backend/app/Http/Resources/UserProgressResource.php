<?php

namespace App\Http\Resources;

use App\Core\Entities\User\UserProgressEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para formatear y exponer la entidad UserProgressEntity
 * como una respuesta JSON limpia para la API.
 */
class UserProgressResource extends JsonResource
{
    /**
     * @var UserProgressEntity
     */
    public $resource;

    /**
     * Constructor para asegurar que el recurso solo acepta la entidad de dominio.
     * @param UserProgressEntity $resource
     */
    public function __construct(UserProgressEntity $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transforma la entidad en un array para ser serializado como JSON.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // El método format() de DateTimeImmutable se usa para garantizar un formato ISO 8601 estándar.
        $lastUsedAt = $this->resource->getLastUsedAt();
        
        return [
            // Identificador primario, null si no ha sido persistido
            'progress_id' => $this->resource->getProgressId(),

            // Claves foráneas (no se exponen todas si no son necesarias, pero aquí se incluyen)
            'user_id' => $this->resource->getUserId(),
            'lesson_id' => $this->resource->getLessonId(),
            'card_id' => $this->resource->getCardId(),
            
            // Atributos de progreso
            'use_count' => $this->resource->getUseCount(),
            'score' => $this->resource->getScore(),
            
            // Fecha, formateada para consumo de API
            'last_used_at' => $lastUsedAt ? $lastUsedAt->format(DATE_ATOM) : null, // Ejemplo: 2024-05-15T10:00:00+00:00

            // Se puede añadir un campo booleano calculado para el frontend
            // Asumiendo que el score 3 es el de completado (regla de negocio del servicio)
            'is_completed' => $this->resource->getScore() >= 3, 
        ];
    }
}
