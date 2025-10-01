<?php

namespace App\Core\Entities;

use DateTime;
use App\Models\UserLesson;

/**
 * Entidad de Dominio para la relación UserLesson.
 * Representa la data y lógica de negocio de la asociación entre un Usuario y una Lección.
 * Utiliza Camel Case.
 */
class UserLessonEntity
{
    /**
     * @param int $userId El ID del usuario.
     * @param int $lessonId El ID de la lección.
     * @param DateTime|null $completedAt La fecha de finalización de la lección.
     * @param UserEntity|null $user La entidad de Usuario (opcional).
     * @param LessonEntity|null $lesson La entidad de Lección (opcional).
     */
    public function __construct(
        public int $userId,
        public int $lessonId,
        public ?DateTime $completedAt = null,
        public ?UserEntity $user = null,
        public ?LessonEntity $lesson = null,
    ) {
    }

    /**
     * Crea una Entidad de Dominio a partir de un array de datos (típicamente de un DTO o Request).
     * Los nombres de las claves deben ser en Camel Case.
     *
     * @param array $data Array asociativo con las propiedades de la entidad.
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $completedAt = $data['completedAt'] ?? null;
        if (is_string($completedAt)) {
            $completedAt = new DateTime($completedAt);
        }

        return new self(
            userId: $data['userId'],
            lessonId: $data['lessonId'],
            completedAt: $completedAt,
        );
    }

    /**
     * Convierte la Entidad a un array para persistencia o respuesta.
     * Usa Snake Case si el repositorio espera columnas de DB o Camel Case si el DTO lo requiere.
     * Para este ejemplo, usamos Snake Case para el repositorio (DB).
     *
     * @return array
     */
    public function toDatabaseArray(): array
    {
        return [
            'user_id_lesson' => $this->userId,
            'lesson_id_lesson' => $this->lessonId,
            'completed_at' => $this->completedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
