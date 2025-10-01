<?php

namespace App\Core\Interfaces;

use App\Core\Entities\Evaluation\EvaluationQuestion;

/**
 * Contrato de Repositorio para la Entidad EvaluationQuestion.
 * Define las operaciones CRUD (y búsqueda) para las preguntas de evaluación.
 * @package App\Core\Interfaces
 */
interface EvaluationQuestionRepositoryInterface
{
    /**
     * Obtiene todas las preguntas de evaluación.
     *
     * @return EvaluationQuestion[]
     */
    public function all(): array;

    /**
     * Obtiene una pregunta de evaluación por su ID.
     *
     * @param int $id El ID de la pregunta.
     * @return EvaluationQuestion|null
     */
    public function findById(int $id): ?EvaluationQuestion;

    /**
     * Obtiene todas las preguntas asociadas a una evaluación específica.
     *
     * @param int $evaluationId El ID de la evaluación.
     * @return EvaluationQuestion[]
     */
    public function getByEvaluationId(int $evaluationId): array;

    /**
     * Crea una nueva pregunta de evaluación.
     *
     * @param array $data Los datos de la pregunta.
     * @return EvaluationQuestion
     */
    public function create(array $data): EvaluationQuestion;

    /**
     * Actualiza una pregunta de evaluación existente.
     *
     * @param int $id El ID de la pregunta a actualizar.
     * @param array $data Los nuevos datos.
     * @return EvaluationQuestion|null
     */
    public function update(int $id, array $data): ?EvaluationQuestion;

    /**
     * Elimina una pregunta de evaluación por su ID.
     *
     * @param int $id El ID de la pregunta a eliminar.
     * @return bool True si fue eliminada, False si no se encontró.
     */
    public function delete(int $id): bool;
}
