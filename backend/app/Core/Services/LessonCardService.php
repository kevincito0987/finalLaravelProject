<?php

namespace App\Core\Services;

use App\Core\Entities\Lessons\LessonCardEntity;
use App\Core\Interfaces\LessonCardRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Servicio de Lógica de Negocio para la gestión de LessonCard.
 *
 * Contiene la lógica para añadir, reordenar y eliminar tarjetas
 * dentro de una lección específica.
 */
class LessonCardService
{
    protected LessonCardRepositoryInterface $lessonCardRepository;

    /**
     * Constructor del servicio.
     *
     * Inyecta la dependencia de la interfaz del repositorio.
     * @param LessonCardRepositoryInterface $lessonCardRepository
     */
    public function __construct(LessonCardRepositoryInterface $lessonCardRepository)
    {
        $this->lessonCardRepository = $lessonCardRepository;
    }

    /**
     * Añade una nueva tarjeta a una lección.
     *
     * Si la tarjeta ya existe en la lección, actualiza su orden.
     * @param LessonCardEntity $entity
     * @return LessonCardEntity
     */
    public function addCardToLesson(LessonCardEntity $entity): LessonCardEntity
    {
        // Lógica de negocio: Por ejemplo, podríamos validar aquí si el orden es lógico
        // o si existe la Lesson y la Card individualmente antes de guardar la asociación.

        // Por simplicidad, delegamos la persistencia al repositorio.
        return $this->lessonCardRepository->save($entity);
    }

    /**
     * Reordena las tarjetas dentro de una lección.
     *
     * Este método aceptaría una colección de entidades o un array asociativo
     * con la nueva estructura de orden.
     *
     * @param int $lessonId ID de la lección a reordenar.
     * @param array<int, int> $cardOrders Array asociativo: [cardId => newOrder]
     * @return bool True si todas las actualizaciones fueron exitosas.
     */
    public function reorderLessonCards(int $lessonId, array $cardOrders): bool
    {
        $success = true;

        // Idealmente, esto se haría dentro de una transacción de base de datos
        // para asegurar que todos los cambios se apliquen o ninguno.

        foreach ($cardOrders as $cardId => $newOrder) {
            $updated = $this->lessonCardRepository->updateOrder(
                $lessonId,
                $cardId,
                $newOrder
            );

            if (!$updated) {
                // Si falla la actualización de una, marcamos como fallido,
                // aunque intentamos continuar con las demás (dependiendo de la lógica).
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Obtiene la lista ordenada de tarjetas para una lección.
     *
     * @param int $lessonId
     * @return Collection<LessonCardEntity>
     */
    public function getOrderedCards(int $lessonId): Collection
    {
        return $this->lessonCardRepository->getCardsByLessonId($lessonId);
    }

    /**
     * Elimina una tarjeta de una lección específica.
     *
     * @param int $lessonId
     * @param int $cardId
     * @return bool
     */
    public function removeCardFromLesson(int $lessonId, int $cardId): bool
    {
        // Lógica de negocio: Aquí podrías añadir un check de permisos,
        // o alguna acción de limpieza antes de eliminar.
        return $this->lessonCardRepository->delete($lessonId, $cardId);
    }
}
