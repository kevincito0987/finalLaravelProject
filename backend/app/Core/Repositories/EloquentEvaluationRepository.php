<?php

namespace App\Core\Repositories;

use App\Core\Entities\EvaluationEntity;
use App\Core\Interfaces\EvaluationRepositoryInterface;
use App\Models\Evaluation;
use Illuminate\Support\Collection;

/**
 * Implementación concreta del Repositorio de Evaluación usando Eloquent ORM.
 * Adapta los modelos de Eloquent a las Entidades Puras del Core.
 */
class EloquentEvaluationRepository implements EvaluationRepositoryInterface
{
    protected Evaluation $model;

    public function __construct(Evaluation $model)
    {
        $this->model = $model;
    }

    // --- Mappers ---

    /**
     * Convierte un Modelo de Eloquent a una Entidad Pura.
     * @param Evaluation $model
     * @return EvaluationEntity
     */
    private function toEntity(Evaluation $model): EvaluationEntity
    {
        return new EvaluationEntity(
            evaluation_id: $model->evaluation_id,
            lesson_id_evaluation: $model->lesson_id_evaluation,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }

    /**
     * Convierte una Colección de Modelos de Eloquent a una Colección de Entidades Puras.
     * @param Collection $models
     * @return Collection<EvaluationEntity>
     */
    private function toEntityCollection(Collection $models): Collection
    {
        return $models->map(fn ($model) => $this->toEntity($model));
    }


    // --- Implementación de la Interfaz ---

    public function getAllEvaluations(): Collection
    {
        $models = $this->model->all();
        return $this->toEntityCollection($models);
    }

    public function findEvaluationById(int $id): ?EvaluationEntity
    {
        $model = $this->model->find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findEvaluationByLessonId(int $lessonId): ?EvaluationEntity
    {
        $model = $this->model->where('lesson_id_evaluation', $lessonId)->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function createEvaluation(EvaluationEntity $entity): EvaluationEntity
    {
        // Los atributos de la entidad se mapean a los campos del modelo Eloquent
        $model = $this->model->create([
            'lesson_id_evaluation' => $entity->lesson_id_evaluation,
            // Aquí puedes añadir otros campos si los tuvieras
        ]);

        // Retorna la entidad recién creada con el ID autogenerado y las timestamps
        return $this->toEntity($model);
    }

    public function updateEvaluation(EvaluationEntity $entity): EvaluationEntity
    {
        $model = $this->model->findOrFail($entity->evaluation_id);

        $model->update([
            'lesson_id_evaluation' => $entity->lesson_id_evaluation,
            // Actualiza otros campos si los tuvieras en la entidad
        ]);

        // Retorna la entidad con los datos actualizados (incluyendo updated_at)
        return $this->toEntity($model);
    }

    public function deleteEvaluation(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
