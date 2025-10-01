<?php

namespace App\Core\Entities\User;

use DateTimeImmutable;

/**
 * Representación pura del progreso del usuario en una Tarjeta específica dentro de una Lección específica.
 *
 * Esta entidad debe ser agnóstica a la base de datos (Eloquent o cualquier ORM).
 */
class UserProgressEntity
{
    private ?int $progressId;
    private int $userId;
    private int $lessonId;      // Clave que apunta a Lesson (parte de la clave compuesta de LessonCard)
    private int $cardId;        // Clave que apunta a Card (parte de la clave compuesta de LessonCard)
    private int $useCount;
    private int $score;         // Puntuación o nivel de dominio de la tarjeta
    private ?DateTimeImmutable $lastUsedAt;

    public function __construct(
        ?int $progressId,
        int $userId,
        int $lessonId,
        int $cardId,
        int $useCount,
        int $score,
        ?DateTimeImmutable $lastUsedAt = null
    ) {
        $this->progressId = $progressId;
        $this->userId = $userId;
        $this->lessonId = $lessonId;
        $this->cardId = $cardId;
        $this->useCount = $useCount;
        $this->score = $score;
        $this->lastUsedAt = $lastUsedAt;
    }

    // --- Getters: Acceso de solo lectura a las propiedades ---

    public function getProgressId(): ?int
    {
        return $this->progressId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    public function getCardId(): int
    {
        return $this->cardId;
    }

    public function getUseCount(): int
    {
        return $this->useCount;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getLastUsedAt(): ?DateTimeImmutable
    {
        return $this->lastUsedAt;
    }

    // --- Setters: Métodos para actualizar el estado del progreso ---

    public function setUseCount(int $useCount): void
    {
        $this->useCount = $useCount;
    }
    
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function setLastUsedAt(?DateTimeImmutable $lastUsedAt): void
    {
        $this->lastUsedAt = $lastUsedAt;
    }
}
