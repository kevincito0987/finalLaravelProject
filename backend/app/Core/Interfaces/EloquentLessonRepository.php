<?php

namespace App\Core\Interfaces;

use App\Core\Entities\LessonEntity;
use App\Core\Repositories\LessonRepositoryInterface;
use App\Models\Lesson; // Asegúrate de que este es el modelo correcto
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Implementación concreta del contrato LessonRepositoryInterface usando Eloquent.
 * Realiza el mapeo bidireccional entre el Modelo Eloquent (Lesson) y la Entidad (LessonEntity).
 */
class EloquentLessonRepository implements LessonRepositoryInterface
{
    protected Lesson $model;

    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }

    // =================================================================
    // Mapeo Bidireccional
    // =================================================================

    /**
     * Convierte un modelo Eloquent Lesson a una Entidad LessonEntity.
     * * Este método es el crucial donde se resolvió el error de 'null'. 
     * Asume que las columnas en la DB son: lesson_id, lessonName, description, lessonType.
     *
     * @param Lesson $model El modelo de Eloquent.
     * @return LessonEntity
     */
    private function toEntity(Lesson $model): LessonEntity
    {
        // Mapeo directo de las propiedades del modelo (accedidas por nombre de columna)
        // a los argumentos del constructor de la Entidad.
        return new LessonEntity(
            lessonName: $model->lessonName,
            description: $model->description,
            lessonType: $model->lessonType,
            lessonId: $model->lesson_id 
        );
    }

    /**
     * Convierte una Entidad LessonEntity a un array compatible con el Modelo Eloquent.
     * @param LessonEntity $entity
     * @return array
     */
    private function toModelData(LessonEntity $entity): array
    {
        // Mapeo de las propiedades de la Entidad a los nombres de columna de la DB.
        return [
            'lessonName' => $entity->lessonName,
            'description' => $entity->description,
            'lessonType' => $entity->lessonType,
        ];
    }

    // =================================================================
    // Implementación del Contrato LessonRepositoryInterface
    // =================================================================

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->model->paginate($perPage);

        // Mapea la colección de Modelos a Entidades
        $items = $paginator->getCollection()->map(fn($model) => $this->toEntity($model));

        // Retorna un nuevo paginador que contiene la colección de Entidades
        return new LengthAwarePaginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->path()] // Se simplifica la construcción del paginador
        );
    }

    public function findById(int $id): ?LessonEntity
    {
        $model = $this->model->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(LessonEntity $lessonEntity): LessonEntity
    {
        // 1. Convertir la Entidad a datos del Modelo
        $model = $this->model->create($this->toModelData($lessonEntity));

        // 2. Convertir el Modelo creado (que ahora tiene el ID) de nuevo a Entidad
        return $this->toEntity($model);
    }

    public function update(int $id, LessonEntity $lessonEntity): LessonEntity
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new ModelNotFoundException("Lección con ID {$id} no encontrada para actualizar.");
        }

        $updateData = $this->toModelData($lessonEntity);
        
        // El método update solo debe recibir los campos que se van a actualizar
        $model->update($updateData);

        // Devolvemos la entidad con el ID y los datos actualizados
        return $this->toEntity($model->refresh());
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
