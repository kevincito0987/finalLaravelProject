<?php

namespace App\Core\Entities\Evaluation;

/**
 * Entidad pura que representa una Evaluación.
 * Contiene solo atributos y el constructor, desvinculada del ORM (Eloquent).
 * * Basado en la Clean Architecture, esta clase contiene las reglas de negocio
 * de la Evaluación (aunque solo se definen los campos aquí).
 */
class EvaluationEntity
{
    /**
     * @var int|null ID de la evaluación (Clave Primaria).
     */
    public ?int $evaluation_id;

    /**
     * @var int ID de la Lección asociada (Clave Foránea).
     */
    public int $lesson_id_evaluation;

    /**
     * @var string|null Marca de tiempo de creación.
     */
    public ?string $created_at;

    /**
     * @var string|null Marca de tiempo de última actualización.
     */
    public ?string $updated_at;

    /**
     * Constructor de la Entidad.
     * * @param int $lesson_id_evaluation ID de la lección requerida.
     * @param int|null $evaluation_id Opcional: ID si la entidad ya existe.
     * @param string|null $created_at
     * @param string|null $updated_at
     */
    public function __construct(
        int $lesson_id_evaluation,
        ?int $evaluation_id = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->lesson_id_evaluation = $lesson_id_evaluation;
        $this->evaluation_id = $evaluation_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
