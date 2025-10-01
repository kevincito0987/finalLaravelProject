<?php

namespace App\Core\Services;

use App\Core\Entities\User\UserLessonEntity;
use App\Core\Interfaces\UserLessonRepositoryInterface;
use Illuminate\Support\Collection;
use Exception;

/**
 * Servicio de Aplicación para gestionar la progresión y finalización de lecciones por parte de los usuarios.
 */
class UserLessonService
{
    public function __construct(
        protected UserLessonRepositoryInterface $repository
    ) {}

    /**
     * Intenta marcar una lección como completada para un usuario.
     *
     * @param int $userId El ID del usuario.
     * @param int $lessonId El ID de la lección.
     * @return UserLessonEntity El registro de lección de usuario actualizado.
     * @throws Exception Si la lección no se pudo marcar como completada.
     */
    public function completeLesson(int $userId, int $lessonId): UserLessonEntity
    {
        try {
            // La lógica de negocio podría ir aquí (ej. verificar permisos, etc.)
            
            // Usamos el Repositorio para persistir el cambio
            $userLesson = $this->repository->markCompleted($userId, $lessonId);

            // Lógica adicional de negocio (ej. otorgar una insignia, etc.)

            return $userLesson;

        } catch (Exception $e) {
            // Manejo de errores específico del negocio
            throw new Exception("No se pudo completar la lección ID {$lessonId} para el usuario ID {$userId}.");
        }
    }

    /**
     * Obtiene la progresión de una lección específica para un usuario.
     *
     * @param int $userId
     * @param int $lessonId
     * @return UserLessonEntity|null
     */
    public function getLessonProgression(int $userId, int $lessonId): ?UserLessonEntity
    {
        return $this->repository->findByKeys($userId, $lessonId);
    }

    /**
     * Obtiene una lista de todas las lecciones completadas por un usuario.
     *
     * @param int $userId
     * @return Collection
     */
    public function getCompletedLessons(int $userId): Collection
    {
        return $this->repository->getCompletedLessonsByUserId($userId);
    }

    /**
     * Obtiene una lista de todas las lecciones pendientes para un usuario.
     *
     * @param int $userId
     * @return Collection
     */
    public function getPendingLessons(int $userId): Collection
    {
        return $this->repository->getPendingLessonsByUserId($userId);
    }
}
