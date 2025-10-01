<?php

namespace App\Core\Services;

use App\Core\Entities\User\UserProgressEntity;
use Illuminate\Support\Collection; // ¡Importación necesaria!

/**
 * Define las operaciones de negocio relacionadas con el progreso del usuario en las tarjetas.
 * Esta capa maneja la lógica de negocio y utiliza el repositorio para la persistencia.
 */
interface UserProgressServiceInterface
{
    /**
     * Marca una tarjeta específica de una lección como "completada" o actualiza el nivel de dominio.
     * Esta es la operación central de registro de progreso.
     *
     * @param int $userId ID del usuario que registra el progreso.
     * @param int $lessonId ID de la lección a la que pertenece la tarjeta.
     * @param int $cardId ID de la tarjeta cuyo progreso se está actualizando.
     * @param int $newMasteryLevel El nuevo nivel de dominio alcanzado (e.g., 1, 2, 3).
     * @return UserProgressEntity La entidad de progreso actualizada o recién creada.
     */
    public function registerCardProgress(
        int $userId,
        int $lessonId,
        int $cardId,
        int $newMasteryLevel
    ): UserProgressEntity;

    /**
     * Obtiene el progreso actual de una tarjeta específica para un usuario.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $cardId
     * @return UserProgressEntity|null
     */
    public function getCurrentCardProgress(
        int $userId,
        int $lessonId,
        int $cardId
    ): ?UserProgressEntity;

    /**
     * Determina si una tarjeta específica ha sido completada (alcance el nivel de dominio deseado).
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $cardId
     * @return bool
     */
    public function isCardCompleted(int $userId, int $lessonId, int $cardId): bool;

    /**
     * Obtiene todos los progresos registrados para un usuario específico.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<int, UserProgressEntity>
     */
    public function getAllUserProgress(int $userId): Collection;

    /**
     * Elimina una entidad de progreso de usuario de la persistencia.
     *
     * @param UserProgressEntity $progressEntity La entidad a eliminar.
     * @return bool True si se eliminó con éxito, False en caso contrario.
     */
    public function deleteProgress(UserProgressEntity $progressEntity): bool;
}
