<?php

namespace App\Core\Interfaces;

use App\Core\Entities\LessonEntity; // Necesario para tipar el retorno de las colecciones
use App\Core\Entities\User\UserLessonEntity;
use Illuminate\Support\Collection;

/**
 * Define el contrato para la gestión de datos de UserLesson.
 * Los métodos solo manejan Entidades de Dominio (UserLessonEntity).
 */
interface UserLessonRepositoryInterface
{
    /**
     * Marca una lección como completada por un usuario.
     *
     * @param int $userId El ID del usuario.
     * @param int $lessonId El ID de la lección a completar.
     * @return UserLessonEntity
     */
    public function markCompleted(int $userId, int $lessonId): UserLessonEntity;

    /**
     * Obtiene el registro de UserLesson para un usuario y una lección específicos.
     *
     * @param int $userId El ID del usuario.
     * @param int $lessonId El ID de la lección.
     * @return UserLessonEntity|null
     */
    public function findByKeys(int $userId, int $lessonId): ?UserLessonEntity;

    /**
     * Obtiene todas las lecciones completadas por un usuario.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<int, LessonEntity> Una colección de LessonEntity.
     */
    public function getCompletedLessonsByUserId(int $userId): Collection;

    /**
     * Obtiene todas las lecciones no completadas por un usuario.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<int, LessonEntity> Una colección de LessonEntity.
     */
    public function getPendingLessonsByUserId(int $userId): Collection;
}
