<?php

namespace App\Core\Interfaces;

use App\Core\Entities\EvaluationEntity;
use Illuminate\Support\Collection;

/**
 * Interface de Repositorio para la entidad Evaluation.
 * Define el contrato de persistencia de datos (CRUD) usando la Entidad Pura.
 */
interface EvaluationRepositoryInterface
{
    /**
     * Obtiene todas las evaluaciones.
     * @return Collection<EvaluationEntity> Una colección de objetos EvaluationEntity.
     */
    public function getAllEvaluations(): Collection;

    /**
     * Busca una evaluación por su ID.
     * @param int $id El ID de la evaluación (evaluation_id).
     * @return EvaluationEntity|null La entidad si se encuentra, o null.
     */
    public function findEvaluationById(int $id): ?EvaluationEntity;

    /**
     * Busca una evaluación por el ID de la lección asociada (FK).
     * Dado que es una relación 1:1, solo debería haber una o ninguna.
     * @param int $lessonId El ID de la lección (lesson_id).
     * @return EvaluationEntity|null La entidad si se encuentra, o null.
     */
    public function findEvaluationByLessonId(int $lessonId): ?EvaluationEntity;

    /**
     * Crea una nueva evaluación.
     * @param EvaluationEntity $evaluation La entidad a crear (solo con lesson_id_evaluation).
     * @return EvaluationEntity La entidad creada con su nuevo ID (PK).
     */
    public function createEvaluation(EvaluationEntity $evaluation): EvaluationEntity;

    /**
     * Actualiza una evaluación existente.
     * @param EvaluationEntity $evaluation La entidad con los datos a actualizar.
     * @return EvaluationEntity La entidad actualizada.
     */
    public function updateEvaluation(EvaluationEntity $evaluation): EvaluationEntity;

    /**
     * Elimina una evaluación por su ID.
     * @param int $id El ID de la evaluación (evaluation_id).
     * @return bool True si se eliminó, false si no se encontró o falló.
     */
    public function deleteEvaluation(int $id): bool;
}
