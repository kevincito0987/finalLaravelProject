<?php

namespace App\Core\Interfaces;

use App\Core\Entities\User\UserProgressEntity;
use Illuminate\Support\Collection; // ¡Importación necesaria para el retorno de findAllByUserId!

/**
 * Define el contrato para el repositorio de progreso del usuario.
 * Permite buscar, guardar y actualizar el progreso sin depender del ORM (Eloquent).
 */
interface UserProgressRepositoryInterface
{
    /**
     * Busca un registro de progreso por su clave primaria (progress_id).
     *
     * @param int $progressId
     * @return UserProgressEntity|null
     */
    public function findById(int $progressId): ?UserProgressEntity;

    /**
     * Busca el registro de progreso único para una Tarjeta específica de una Lección
     * para un Usuario dado.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $cardId
     * @return UserProgressEntity|null
     */
    public function findByKeys(int $userId, int $lessonId, int $cardId): ?UserProgressEntity;

    /**
     * Obtiene todos los progresos registrados para un usuario específico.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<int, UserProgressEntity> Una colección de entidades de progreso.
     */
    public function findAllByUserId(int $userId): Collection;

    /**
     * Guarda un nuevo registro de progreso o actualiza uno existente.
     *
     * Si la entidad tiene progressId, se intenta actualizar.
     * Si no tiene progressId (null), se intenta crear uno nuevo.
     *
     * @param UserProgressEntity $entity
     * @return UserProgressEntity La entidad guardada, con el progressId si se creó.
     */
    public function save(UserProgressEntity $entity): UserProgressEntity;

    /**
     * Elimina un registro de progreso.
     *
     * @param UserProgressEntity $entity
     * @return bool
     */
    public function delete(UserProgressEntity $entity): bool;
}
