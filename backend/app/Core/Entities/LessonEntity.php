<?php

namespace App\Core\Entities;

use InvalidArgumentException;
use Illuminate\Support\Str;

/**
 * Entidad de Lección.
 * Esta clase es inmutable, representando un objeto de valor para el dominio.
 */
class LessonEntity
{
    // Las propiedades se hacen públicas para fácil acceso (Value Object)
    public readonly ?int $lessonId;
    public readonly string $lessonName;
    public readonly string $description;
    public readonly string $lessonType;

    /**
     * Constructor de la entidad Lesson.
     *
     * @param string $lessonName El título de la lección.
     * @param string $description La descripción de la lección.
     * @param string $lessonType El tipo de lección (ej: 'video', 'quiz', 'text').
     * @param int|null $lessonId ID de la lección (opcional, solo para objetos que vienen de DB).
     * @throws InvalidArgumentException Si la data proporcionada es inválida.
     */
    public function __construct(
        string $lessonName,
        string $description,
        string $lessonType,
        ?int $lessonId = null
    ) {
        $this->validate($lessonName, $description, $lessonType);

        $this->lessonId = $lessonId;
        $this->lessonName = $lessonName;
        $this->description = $description;
        $this->lessonType = $lessonType;
    }

    /**
     * Realiza las validaciones de la entidad.
     *
     * @param string $lessonName
     * @param string $description
     * @param string $lessonType
     * @return void
     * @throws InvalidArgumentException
     */
    protected function validate(string $lessonName, string $description, string $lessonType): void
    {
        if (empty(trim($lessonName))) {
            throw new InvalidArgumentException("El nombre de la lección no puede estar vacío.");
        }

        if (strlen($lessonName) > 255) {
            throw new InvalidArgumentException("El nombre de la lección no puede exceder los 255 caracteres.");
        }

        if (empty(trim($description))) {
            throw new InvalidArgumentException("La descripción no puede estar vacía.");
        }

        // Validación simple del tipo: Podríamos expandir esto a una lista estricta (enum).
        if (empty(trim($lessonType))) {
            throw new InvalidArgumentException("El tipo de lección no puede estar vacío.");
        }
    }

    /**
     * Crea una nueva instancia de la entidad fusionando los datos actuales con los nuevos.
     * Útil para operaciones de actualización (Update).
     *
     * @param array $newData Array asociativo de nuevos datos (lessonName, description, lessonType).
     * @return LessonEntity Una nueva instancia inmutable con los datos actualizados.
     */
    public function updateWith(array $newData): LessonEntity
    {
        return new LessonEntity(
            $newData['lessonName'] ?? $this->lessonName,
            $newData['description'] ?? $this->description,
            $newData['lessonType'] ?? $this->lessonType,
            $this->lessonId // El ID es inmutable
        );
    }
}
