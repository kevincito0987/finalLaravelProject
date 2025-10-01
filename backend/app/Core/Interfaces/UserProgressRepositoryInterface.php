<?php

namespace App\Core\Interfaces;

use App\Core\Entities\UserProgressEntity;
use Illuminate\Support\Collection;

interface UserProgressRepositoryInterface
{
    /**
     * Busca un registro de progreso por ID.
     *
     * @param int $id El ID del progreso (progress_id).
     * @return UserProgressEntity|null
     */
    public function findById(int $id): ?UserProgressEntity;

    /**
     * Busca el progreso específico de un usuario para una tarjeta.
     *
     * @param int $userId El ID del usuario.
     * @param int $cardId El ID de la tarjeta.
     * @return UserProgressEntity|null
     */
    public function findByUserIdAndCardId(int $userId, int $cardId): ?UserProgressEntity;

    /**
     * Obtiene todo el progreso de un usuario.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<UserProgressEntity>
     */
    public function getProgressByUserId(int $userId): Collection;

    /**
     * Guarda o actualiza un registro de progreso.
     *
     * @param UserProgressEntity $progress La entidad a persistir.
     * @return UserProgressEntity
     */
    public function save(UserProgressEntity $progress): UserProgressEntity;
}
