<?php

namespace App\Services;

use App\Core\Entities\User\UserProgressEntity;
use App\Core\Interfaces\UserProgressRepositoryInterface;
use Illuminate\Support\Collection;

class UserProgressService
{
    private UserProgressRepositoryInterface $userProgressRepository;

    /**
     * Inyección de dependencia de la interfaz del repositorio.
     */
    public function __construct(UserProgressRepositoryInterface $userProgressRepository)
    {
        $this->userProgressRepository = $userProgressRepository;
    }

    /**
     * Obtiene o crea el registro de progreso para una tarjeta dada y marca el uso.
     * Este es el Use Case principal para interactuar con el progreso.
     *
     * @param int $userId ID del usuario actual.
     * @param int $cardId ID de la tarjeta usada.
     * @return UserProgressEntity
     */
    public function updateOrInitializeProgress(int $userId, int $cardId): UserProgressEntity
    {
        // 1. Intentar encontrar el progreso existente
        $progress = $this->userProgressRepository->findByUserIdAndCardId($userId, $cardId);

        if (!$progress) {
            // 2. Si no existe, inicializar una nueva entidad
            $progress = new UserProgressEntity(
                progressId: null,
                userIdProgress: $userId,
                cardIdProgress: $cardId,
                useCount: 0,
                lastUsedAt: null
            );
        }

        // 3. Aplicar la lógica de negocio a la entidad (incrementar contador y actualizar fecha)
        $progress->markUsed();

        // 4. Persistir los cambios usando el repositorio
        return $this->userProgressRepository->save($progress);
    }
    
    /**
     * Obtiene todo el progreso de un usuario específico.
     * * @param int $userId
     * @return Collection<UserProgressEntity>
     */
    public function getUserProgress(int $userId): Collection
    {
        return $this->userProgressRepository->getProgressByUserId($userId);
    }
}
