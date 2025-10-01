<?php

namespace App\Repositories;

use App\Core\Entities\UserProgressEntity;
use App\Core\Interfaces\UserProgressRepositoryInterface;
use App\Models\UserProgress;
use Illuminate\Support\Collection;
use DateTime;
use Exception;

/**
 * Implementación del repositorio de progreso de usuario usando Eloquent.
 */
class EloquentUserProgressRepository implements UserProgressRepositoryInterface
{
    private UserProgress $model;

    public function __construct(UserProgress $model)
    {
        $this->model = $model;
    }

    /**
     * Mapea un Modelo Eloquent a una Entidad de Dominio.
     */
    private function toEntity(UserProgress $model): UserProgressEntity
    {
        // El casting 'datetime' en el modelo Eloquent ya convierte last_used_at a Carbon.
        // Carbon implementa la interfaz DateTimeInterface, por lo que podemos usarlo.
        return new UserProgressEntity(
            progressId: $model->progress_id,
            userIdProgress: $model->user_id_progress,
            cardIdProgress: $model->card_id_progress,
            useCount: $model->use_count,
            lastUsedAt: $model->last_used_at?->toDateTime() // Convertir a DateTime estándar si existe
        );
    }

    /**
     * Mapea una Entidad de Dominio a un array para guardar en Eloquent.
     */
    private function toModelArray(UserProgressEntity $entity): array
    {
        return [
            // progress_id se omite para la creación o se usa para la actualización.
            'user_id_progress' => $entity->getUserIdProgress(),
            'card_id_progress' => $entity->getCardIdProgress(),
            'use_count' => $entity->getUseCount(),
            // Formatear DateTime a string de base de datos
            'last_used_at' => $entity->getLastUsedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public function findById(int $id): ?UserProgressEntity
    {
        $model = $this->model->find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByUserIdAndCardId(int $userId, int $cardId): ?UserProgressEntity
    {
        $model = $this->model
            ->where('user_id_progress', $userId)
            ->where('card_id_progress', $cardId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function getProgressByUserId(int $userId): Collection
    {
        $models = $this->model
            ->where('user_id_progress', $userId)
            // Cargar la relación 'card' si es necesario para el uso en el servicio/controlador
            // ->with('card') 
            ->get();

        return $models->map(fn($model) => $this->toEntity($model));
    }

    public function save(UserProgressEntity $progress): UserProgressEntity
    {
        $data = $this->toModelArray($progress);
        
        if ($progress->getProgressId()) {
            // Actualización
            $model = $this->model->findOrFail($progress->getProgressId());
            $model->update($data);
        } else {
            // Creación
            $model = $this->model->create($data);
            // Aseguramos que la entidad refleje el ID recién creado
            $progress = $this->toEntity($model);
        }

        return $progress;
    }
}
