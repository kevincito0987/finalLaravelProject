<?php

namespace App\Core\Services;

use App\Core\Entities\User\UserProgressEntity;
use App\Core\Interfaces\UserProgressRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Support\Collection; // Necesario para el tipo de retorno

/**
 * Implementación de la lógica de negocio para gestionar el progreso del usuario.
 * Esta clase se encarga de aplicar las reglas de negocio antes de interactuar con el Repositorio.
 */
class UserProgressService implements UserProgressServiceInterface
{
    /**
     * Define la puntuación (Score) que se considera "completado" o dominado (Mastered) para una tarjeta.
     */
    private const COMPLETED_SCORE = 3;

    private UserProgressRepositoryInterface $userProgressRepository;

    /**
     * Inyección de Dependencia del Repositorio.
     *
     * @param UserProgressRepositoryInterface $userProgressRepository
     */
    public function __construct(UserProgressRepositoryInterface $userProgressRepository)
    {
        // Inyectamos la interfaz del repositorio, no la implementación Eloquent concreta.
        $this->userProgressRepository = $userProgressRepository;
    }

    /**
     * @inheritDoc
     */
    public function registerCardProgress(
        int $userId,
        int $lessonId,
        int $cardId,
        int $newMasteryLevel
    ): UserProgressEntity {
        // 1. Buscar si ya existe un progreso para esta tarjeta.
        $progress = $this->userProgressRepository->findByKeys($userId, $lessonId, $cardId);
        $currentTime = new DateTimeImmutable('now');

        if (!$progress) {
            // Caso A: No existe, crear una nueva entidad de progreso.
            $progress = new UserProgressEntity(
                progressId: null, // El repositorio/base de datos asignará el ID
                userId: $userId,
                lessonId: $lessonId,
                cardId: $cardId,
                useCount: 1, // Primer uso
                score: $newMasteryLevel,
                lastUsedAt: $currentTime,
            );
        } else {
            // Caso B: Ya existe, aplicar la lógica de actualización.
            $currentScore = $progress->getScore();

            // Lógica de negocio:
            $progress->setUseCount($progress->getUseCount() + 1);

            if ($newMasteryLevel >= $currentScore) {
                $progress->setScore($newMasteryLevel);
            }

            $progress->setLastUsedAt($currentTime);
        }

        // 2. Persistir o actualizar la entidad a través del Repositorio.
        return $this->userProgressRepository->save($progress);
    }

    /**
     * @inheritDoc
     */
    public function getCurrentCardProgress(
        int $userId,
        int $lessonId,
        int $cardId
    ): ?UserProgressEntity {
        // Simplemente delegamos la búsqueda al repositorio
        return $this->userProgressRepository->findByKeys($userId, $lessonId, $cardId);
    }

    /**
     * @inheritDoc
     */
    public function isCardCompleted(int $userId, int $lessonId, int $cardId): bool
    {
        $progress = $this->getCurrentCardProgress($userId, $lessonId, $cardId);

        if (!$progress) {
            return false;
        }

        // Regla de Negocio: La tarjeta se considera completada si el score es igual o mayor
        // al nivel mínimo requerido (COMPLETED_SCORE).
        return $progress->getScore() >= self::COMPLETED_SCORE;
    }

    /**
     * @inheritDoc
     */
    public function deleteProgress(UserProgressEntity $progressEntity): bool
    {
        // Regla de Negocio simple: solo delegar la eliminación al repositorio.
        return $this->userProgressRepository->delete($progressEntity);
    }

    /**
     * Obtiene todos los progresos registrados para un usuario específico.
     *
     * @param int $userId El ID del usuario.
     * @return Collection<int, UserProgressEntity>
     */
    public function getAllUserProgress(int $userId): Collection
    {
        // Regla de Negocio: Delegar al repositorio la obtención de todos los progresos de un usuario.
        return $this->userProgressRepository->findAllByUserId($userId);
    }
}
