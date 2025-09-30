<?php

namespace App\Core\Entities;

/**
 * Entidad de Dominio para Evaluation Question (Pregunta de Evaluación).
 * Representa la estructura de la pregunta de forma agnóstica a la persistencia (sin Laravel/Eloquent).
 */
class EvaluationQuestion
{
    public int $questionId;
    public int $evaluationId; // FK de la evaluación
    public int $cardId;       // FK de la tarjeta (LessonCard)
    public string $questionText;
    public string $correctAnswer;
    public array $options; // Se asume que esto se almacenará como JSON/TEXT en la DB

    /**
     * Constructor de la entidad.
     *
     * @param int $questionId
     * @param int $evaluationId
     * @param int $cardId
     * @param string $questionText
     * @param string $correctAnswer
     * @param array $options
     */
    public function __construct(
        int $questionId,
        int $evaluationId,
        int $cardId,
        string $questionText,
        string $correctAnswer,
        array $options
    ) {
        $this->questionId = $questionId;
        $this->evaluationId = $evaluationId;
        $this->cardId = $cardId;
        $this->questionText = $questionText;
        $this->correctAnswer = $correctAnswer;
        $this->options = $options;
    }
}
