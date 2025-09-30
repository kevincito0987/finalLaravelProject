<?php

namespace App\Core\Services;

use App\Core\Entities\LessonEntity;
use App\Core\Repositories\LessonRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Servicio de Aplicación para Lecciones.
 * Contiene la lógica de negocio y utiliza el repositorio para la persistencia.
 */
class LessonService
{
    protected LessonRepositoryInterface $lessonRepository;

    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        // El servicio siempre depende del CONTRATO (Interface), no de la implementación Eloquent.
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Obtiene una lista paginada de todas las lecciones.
     *
     * @param int $perPage
     * @return LengthAwarePaginator<LessonEntity>
     */
    public function getAllLessons(int $perPage = 15): LengthAwarePaginator
    {
        return $this->lessonRepository->getAll($perPage);
    }

    /**
     * Busca una lección por su ID.
     *
     * @param int $id
     * @return LessonEntity
     * @throws ModelNotFoundException Si la lección no existe.
     */
    public function getLesson(int $id): LessonEntity
    {
        $lesson = $this->lessonRepository->findById($id);

        if (!$lesson) {
            throw new ModelNotFoundException("Lección con ID {$id} no encontrada.");
        }

        return $lesson;
    }

    /**
     * Crea una nueva lección.
     * Nota: La validación de datos se realiza en el constructor de LessonEntity.
     *
     * @param LessonEntity $lessonEntity
     * @return LessonEntity
     */
    public function createLesson(LessonEntity $lessonEntity): LessonEntity
    {
        // Aquí podrías añadir lógica de negocio antes de la persistencia, como:
        // - Asignar un autor.
        // - Enviar una notificación.
        // - Inicializar un recurso asociado.

        return $this->lessonRepository->create($lessonEntity);
    }

    /**
     * Actualiza una lección existente.
     *
     * @param int $id
     * @param LessonEntity $newLessonEntity Entidad con los datos potencialmente nuevos.
     * @return LessonEntity
     * @throws ModelNotFoundException Si la lección no existe.
     */
    public function updateLesson(int $id, LessonEntity $newLessonEntity): LessonEntity
    {
        $existingLesson = $this->lessonRepository->findById($id);

        if (!$existingLesson) {
            throw new ModelNotFoundException("Lección con ID {$id} no encontrada para actualizar.");
        }
        
        // El repositorio de Eloquent ya implementa la lógica de solo actualizar campos no nulos.
        // Así que podemos pasar directamente la entidad.
        
        return $this->lessonRepository->update($id, $newLessonEntity);
    }

    /**
     * Elimina una lección por su ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException Si la lección no existe.
     */
    public function deleteLesson(int $id): bool
    {
        $existingLesson = $this->lessonRepository->findById($id);

        if (!$existingLesson) {
            throw new ModelNotFoundException("Lección con ID {$id} no encontrada para eliminar.");
        }
        
        // Aquí podrías añadir lógica de negocio antes de la eliminación, como:
        // - Eliminar recursos relacionados (imágenes, archivos).
        // - Desvincular de otros modelos (e.g., cursos).
        
        return $this->lessonRepository->delete($id);
    }
}
