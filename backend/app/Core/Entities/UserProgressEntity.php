<?php

namespace App\Core\Entities;

use DateTime;

/**
 * Entidad que representa el progreso y uso de una tarjeta por parte de un usuario.
 * Utiliza los nombres de columna tal como están definidos en la base de datos.
 */
class UserProgressEntity
{
    private ?int $progressId;
    private int $userIdProgress;
    private int $cardIdProgress;
    private int $useCount;
    private ?DateTime $lastUsedAt;

    /**
     * @param int|null $progressId ID de clave primaria (puede ser nulo al crear)
     * @param int $userIdProgress FK al usuario
     * @param int $cardIdProgress FK a la tarjeta de lección
     * @param int $useCount Contador de uso de la tarjeta
     * @param DateTime|null $lastUsedAt Marca de tiempo de la última vez que se usó
     */
    public function __construct(
        ?int $progressId,
        int $userIdProgress,
        int $cardIdProgress,
        int $useCount,
        ?DateTime $lastUsedAt = null
    ) {
        $this->progressId = $progressId;
        $this->userIdProgress = $userIdProgress;
        $this->cardIdProgress = $cardIdProgress;
        $this->useCount = $useCount;
        $this->lastUsedAt = $lastUsedAt;
    }

    // --- Getters ---

    public function getProgressId(): ?int
    {
        return $this->progressId;
    }

    public function getUserIdProgress(): int
    {
        return $this->userIdProgress;
    }

    public function getCardIdProgress(): int
    {
        return $this->cardIdProgress;
    }

    public function getUseCount(): int
    {
        return $this->useCount;
    }

    public function getLastUsedAt(): ?DateTime
    {
        return $this->lastUsedAt;
    }

    // --- Métodos de Negocio (Ejemplo) ---

    /**
     * Incrementa el contador de uso y actualiza la marca de tiempo de último uso.
     */
    public function markUsed(): void
    {
        $this->useCount++;
        $this->lastUsedAt = new DateTime();
    }
}
