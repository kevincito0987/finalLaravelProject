<?php

namespace App\Core\Interfaces;

use App\Core\Entities\Lessons\LessonCardEntity;
use Illuminate\Support\Collection;

/**
 * Interfaz para el Repositorio de Tarjetas de Lección (LessonCardRepositoryInterface).
 *
 * Define el contrato para las operaciones de persistencia (CRUD y otras específicas)
 * de la entidad LessonCardEntity.
 *
 * Dado que LessonCard es una tabla pivote sin ID simple, las operaciones se basan
 * en la clave compuesta (lessonId, cardId).
 */
interface LessonCardRepositoryInterface
{
    /**
     * Guarda una nueva asociación Card-Lesson o actualiza el orden si ya existe.
     *
     * @param LessonCardEntity $entity La entidad a guardar.
     * @return LessonCardEntity La entidad guardada con cualquier metadato actualizado (aunque en este caso es la misma).
     */
    public function save(LessonCardEntity $entity): LessonCardEntity;

    /**
     * Busca una asociación específica usando la clave compuesta.
     *
     * @param int $lessonId ID de la Lección.
     * @param int $cardId ID de la Tarjeta.
     * @return LessonCardEntity|null La entidad encontrada o null si no existe.
     */
    public function findByKeys(int $lessonId, int $cardId): ?LessonCardEntity;

    /**
     * Elimina una asociación Lesson-Card de la base de datos.
     *
     * @param int $lessonId ID de la Lección.
     * @param int $cardId ID de la Tarjeta.
     * @return bool True si la eliminación fue exitosa, False en caso contrario.
     */
    public function delete(int $lessonId, int $cardId): bool;

    /**
     * Obtiene todas las tarjetas asociadas a una lección específica, ordenadas por 'orderInLesson'.
     *
     * @param int $lessonId ID de la Lección.
     * @return Collection<LessonCardEntity> Colección de entidades LessonCardEntity.
     */
    public function getCardsByLessonId(int $lessonId): Collection;

    /**
     * Actualiza el orden de una tarjeta específica dentro de una lección.
     *
     * @param int $lessonId ID de la Lección.
     * @param int $cardId ID de la Tarjeta.
     * @param int $newOrder Nuevo valor para el orden.
     * @return bool True si la actualización fue exitosa, False en caso contrario.
     */
    public function updateOrder(int $lessonId, int $cardId, int $newOrder): bool;
}
