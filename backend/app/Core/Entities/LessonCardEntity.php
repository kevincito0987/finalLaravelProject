<?php

namespace App\Core\Entities;

use App\Models\LessonCard;
use Illuminate\Http\Request;

/**
 * Entidad de Tarjeta de Lección (LessonCardEntity)
 *
 * Representa la asociación y el orden de una Card dentro de una Lesson.
 * Dada su naturaleza como tabla pivote con clave primaria compuesta.
 */
class LessonCardEntity
{
    /**
     * @var int ID de la Lección a la que pertenece la asociación.
     */
    public readonly int $lessonId;

    /**
     * @var int ID de la Tarjeta asociada a la Lección.
     */
    public readonly int $cardId;

    /**
     * @var int El orden de la tarjeta dentro de la lección (debe ser único por lessonId).
     */
    public readonly int $orderInLesson;

    /**
     * Constructor de la entidad.
     *
     * @param int $lessonId El ID de la lección.
     * @param int $cardId El ID de la tarjeta.
     * @param int $orderInLesson El orden de la tarjeta en la lección.
     */
    public function __construct(
        int $lessonId,
        int $cardId,
        int $orderInLesson
    ) {
        $this->lessonId = $lessonId;
        $this->cardId = $cardId;
        $this->orderInLesson = $orderInLesson;
    }

    /**
     * Crea la entidad a partir de una solicitud (Request) de entrada.
     *
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        // Se asume que la Request contiene los campos necesarios
        // 'lesson_id_sesion', 'card_id_sesion', y 'order_in_lesson'
        return new self(
            (int) $request->input('lesson_id_sesion'),
            (int) $request->input('card_id_sesion'),
            (int) $request->input('order_in_lesson')
        );
    }

    /**
     * Crea la entidad a partir de un modelo LessonCard de Eloquent.
     *
     * @param LessonCard $model El modelo LessonCard de la base de datos.
     * @return self
     */
    public static function fromModel(LessonCard $model): self
    {
        return new self(
            (int) $model->lesson_id_sesion,
            (int) $model->card_id_sesion,
            (int) $model->order_in_lesson
        );
    }

    /**
     * Convierte la entidad en un array asociativo para ser usada por el Repositorio o el Modelo.
     *
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'lesson_id_sesion' => $this->lessonId,
            'card_id_sesion' => $this->cardId,
            'order_in_lesson' => $this->orderInLesson,
        ];
    }
}
