<?php

namespace App\Core\Repositories;

use App\Core\Entities\User\UserProgressEntity;
use App\Core\Interfaces\UserProgressRepositoryInterface;
use App\Models\UserProgress; // Asumimos que este modelo existe
use DateTimeImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Repositorio concreto de progreso de usuario que utiliza Eloquent ORM.
 * Esta clase implementa el contrato de repositorio y realiza la conversión
 * entre el Modelo de Eloquent y la Entidad de Dominio internamente.
 */
class EloquentUserProgressRepository implements UserProgressRepositoryInterface
{
    private UserProgress $model;

    /**
     * Inyección de dependencia del Modelo de Eloquent.
     */
    public function __construct(UserProgress $model)
    {
        $this->model = $model;
    }

    // --- Mapeo Interno de Modelo a Entidad ---

    /**
     * Convierte un Modelo de Eloquent a una Entidad de Dominio.
     */
    private function toEntity(UserProgress $model): UserProgressEntity
    {
        // Se asegura de convertir la fecha a la clase pura DateTimeImmutable,
        // ya que Eloquent devuelve un objeto Carbon.
        $lastUsedAt = $model->last_used_at
            ? new DateTimeImmutable($model->last_used_at->toDateTimeString())
            : null;

        return new UserProgressEntity(
            progressId: $model->id,
            userId: $model->user_id,
            lessonId: $model->lesson_id,
            cardId: $model->card_id,
            useCount: $model->use_count,
            score: $model->score,
            lastUsedAt: $lastUsedAt,
        );
    }

    /**
     * Convierte la Entidad de Dominio a un array para la base de datos.
     */
    private function toDatabaseArray(UserProgressEntity $entity): array
    {
        return [
            'user_id' => $entity->getUserId(),
            'lesson_id' => $entity->getLessonId(),
            'card_id' => $entity->getCardId(),
            'use_count' => $entity->getUseCount(),
            'score' => $entity->getScore(),
            // Convierte DateTimeImmutable a formato de string SQL
            'last_used_at' => $entity->getLastUsedAt() ? $entity->getLastUsedAt()->format('Y-m-d H:i:s') : null,
        ];
    }

    // --- Implementación de la Interfaz ---

    /**
     * @inheritDoc
     */
    public function findById(int $progressId): ?UserProgressEntity
    {
        /** @var UserProgress|null $model */
        $model = $this->model->find($progressId);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function findByKeys(int $userId, int $lessonId, int $cardId): ?UserProgressEntity
    {
        /** @var UserProgress|null $model */
        $model = $this->model
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->where('card_id', $cardId)
            ->first();

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAllByUserId(int $userId): Collection
    {
        // 1. Obtener todos los modelos Eloquent para ese ID de usuario
        $eloquentModels = UserProgress::where('user_id', $userId)->get();

        // 2. Mapear la colección de Modelos a una colección de Entidades
        return $eloquentModels->map(function ($model) {
            return $this->mapModelToEntity($model);
        });
    }

    /**
     * @inheritDoc
     */
    public function save(UserProgressEntity $entity): UserProgressEntity
    {
        // 1. Mapear de Entidad a Modelo o buscar existente
        if ($entity->getProgressId() !== null) {
            // Caso 1: Actualizar existente (por ID primario)
            $model = UserProgress::find($entity->getProgressId());
            if (!$model) {
                // Alternativamente, puedes buscar por claves compuestas
                $model = UserProgress::where([
                    'user_id' => $entity->getUserId(),
                    'lesson_id' => $entity->getLessonId(),
                    'card_id' => $entity->getCardId(),
                ])->first();
            }
        } else {
            // Caso 2: Nuevo registro (sin ID)
            $model = new UserProgress();
            // Llenar claves compuestas para que se guarde correctamente
            $model->user_id = $entity->getUserId();
            $model->lesson_id = $entity->getLessonId();
            $model->card_id = $entity->getCardId();
        }

        // 2. Aplicar los atributos del progreso
        $model->use_count = $entity->getUseCount();
        $model->score = $entity->getScore();
        $model->last_used_at = $entity->getLastUsedAt(); // Asumiendo que Eloquent puede manejar DateTimeImmutable o Carbon/string

        // 3. Persistir en la base de datos
        $model->save();

        // 4. *** CRUCIAL: Mapeo Inverso para obtener el ID ***
        if ($entity->getProgressId() === null && $model->progress_id !== null) {
            // Si era nuevo, actualizamos la Entidad de Dominio con el ID generado.
            // Para hacer esto, necesitamos un Setter en la Entidad para el progressId,
            // O devolver una nueva entidad construida a partir del modelo.
            
            // Opción B: Devolver una Entidad *nueva* y completa (Recomendado en DDD)
            return $this->mapModelToEntity($model);
        }
        
        // Opción A: Devolver la misma Entidad (si se pudo mutar el ID, no es ideal en DDD)
        // return $entity;

        return $this->mapModelToEntity($model); // Asumimos que siempre devolvemos un mapeo limpio.
    }
    
    // Y necesitas un método para mapear el Modelo de Eloquent a tu Entidad de Dominio
    private function mapModelToEntity($model): UserProgressEntity
    {
        // Asegúrate de que $model->last_used_at sea un objeto DateTimeImmutable o null.
        $lastUsedAt = $model->last_used_at 
            ? new DateTimeImmutable($model->last_used_at) 
            : null;
            
        return new UserProgressEntity(
            progressId: $model->progress_id,
            userId: $model->user_id,
            lessonId: $model->lesson_id,
            cardId: $model->card_id,
            useCount: $model->use_count,
            score: $model->score,
            lastUsedAt: $lastUsedAt
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(UserProgressEntity $entity): bool
    {
        $progressId = $entity->getProgressId();

        if ($progressId === null) {
            // No se puede eliminar si no tiene un ID de persistencia
            return false;
        }

        try {
            // Busca por ID y elimina
            return $this->model->findOrFail($progressId)->delete();
        } catch (ModelNotFoundException $e) {
            // El registro ya no existe
            return false;
        }
    }
}
