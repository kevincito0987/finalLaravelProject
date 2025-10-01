<?php

namespace App\Core\Services;

use App\Core\Entities\Evaluation\EvaluationQuestion;
use App\Core\Interfaces\EvaluationQuestionRepositoryInterface;

/**
 * Servicio de Evaluación de Preguntas (Caso de Uso).
 * Contiene la lógica de negocio relacionada con la gestión y validación de las preguntas de evaluación.
 * * @package App\Core\Services
 */
class EvaluationQuestionService
{
    private EvaluationQuestionRepositoryInterface $repository;

    public function __construct(EvaluationQuestionRepositoryInterface $repository)
    {
        // Inyección de dependencia: solo dependemos de la Interfaz.
        $this->repository = $repository;
    }

    /**
     * Obtiene todas las preguntas para una evaluación.
     *
     * @param int $evaluationId
     * @return EvaluationQuestion[]
     */
    public function getQuestionsByEvaluation(int $evaluationId): array
    {
        // Lógica de negocio si fuera necesario antes de obtener, e.g., verificar permisos
        return $this->repository->getByEvaluationId($evaluationId);
    }

    /**
     * Obtiene una pregunta específica.
     *
     * @param int $questionId
     * @return EvaluationQuestion|null
     */
    public function getQuestionById(int $questionId): ?EvaluationQuestion
    {
        return $this->repository->findById($questionId);
    }

    /**
     * Crea una nueva pregunta y realiza validaciones de estructura.
     * * @param array $data Datos de la pregunta.
     * @return EvaluationQuestion
     * @throws \InvalidArgumentException Si la estructura de las opciones es inválida.
     */
    public function createQuestion(array $data): EvaluationQuestion
    {
        // Lógica de validación de negocio: asegurar que la respuesta correcta esté en las opciones.
        if (!in_array($data['correct_answer'], $data['options'])) {
             throw new \InvalidArgumentException("La respuesta correcta debe estar incluida en las opciones de respuesta.");
        }

        // Se podrían añadir otras validaciones de estructura o contenido aquí.

        return $this->repository->create($data);
    }
    
    /**
     * Actualiza una pregunta existente.
     *
     * @param int $questionId
     * @param array $data
     * @return EvaluationQuestion|null
     */
    public function updateQuestion(int $questionId, array $data): ?EvaluationQuestion
    {
        // Se pueden añadir validaciones o transformaciones de datos antes de actualizar.

        return $this->repository->update($questionId, $data);
    }

    /**
     * Elimina una pregunta.
     *
     * @param int $questionId
     * @return bool
     */
    public function deleteQuestion(int $questionId): bool
    {
        return $this->repository->delete($questionId);
    }
}
