<?php

namespace App\Core\Repositories;

use App\Core\Interfaces\EvaluationQuestionRepositoryInterface;
use App\Core\Entities\EvaluationQuestion;
use App\Models\EvaluationQuestion as EvaluationQuestionModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Implementación del Repositorio de Preguntas de Evaluación usando Eloquent.
 * Traduce entre Modelos Eloquent y Entidades de Dominio.
 * @package App\Core\Repositories
 */
class EloquentEvaluationQuestionRepository implements EvaluationQuestionRepositoryInterface
{
    private EvaluationQuestionModel $model;

    public function __construct(EvaluationQuestionModel $model)
    {
        $this->model = $model;
    }

    /**
     * Convierte un Modelo Eloquent a una Entidad de Dominio.
     * @param EvaluationQuestionModel $model
     * @return EvaluationQuestion
     */
    private function toEntity(EvaluationQuestionModel $model): EvaluationQuestion
    {
        return new EvaluationQuestion(
            $model->question_id,
            $model->evaluation_id_question,
            $model->card_id_evaluation,
            $model->question_text,
            $model->correct_answer,
            // 'options' ya es un array gracias al casting en el modelo
            $model->options
        );
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        // 1. Obtener todos los modelos. Se asume que en el modelo Eloquent
        // podrías necesitar cargar las relaciones 'evaluation' y 'card' si el
        // Resource lo requiere, aunque la entidad no las almacene directamente.
        // Si no tienes esas relaciones definidas en tu modelo Eloquent, omite el with().
        $models = $this->model
            // ->with(['evaluation', 'card']) // Descomentar si tu modelo tiene estas relaciones y son necesarias para el Resource
            ->get();

        // 2. Mapear la colección de Modelos Eloquent a un array de Entidades de Dominio
        return $models->map(fn($model) => $this->toEntity($model))->all();
    }
    
    /**
     * @inheritDoc
     */
    public function findById(int $id): ?EvaluationQuestion
    {
        // Al igual que en all(), si necesitas relaciones para el Resource, agrégalas aquí.
        $model = $this->model
            // ->with(['evaluation', 'card']) // Descomentar si se necesitan
            ->find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function getByEvaluationId(int $evaluationId): array
    {
        $models = $this->model
            // ->with(['evaluation', 'card']) // Descomentar si se necesitan
            ->where('evaluation_id_question', $evaluationId)
            ->get();
        
        return $models->map(fn($model) => $this->toEntity($model))->all();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): EvaluationQuestion
    {
        // Nota: Eloquent manejará automáticamente el array 'options' como JSON/TEXT.
        $model = $this->model->create([
            'evaluation_id_question' => $data['evaluation_id_question'],
            'card_id_evaluation' => $data['card_id_evaluation'],
            'question_text' => $data['question_text'],
            'correct_answer' => $data['correct_answer'],
            'options' => $data['options'], // Esto debe ser un array
        ]);

        // Si necesitas las relaciones cargadas después de la creación, usa fresh()
        // $model->loadMissing(['evaluation', 'card']); 

        return $this->toEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data): ?EvaluationQuestion
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->update($data);

            // Si necesitas las relaciones cargadas después de la actualización, usa fresh()
            // $model->loadMissing(['evaluation', 'card']); 
            
            return $this->toEntity($model);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): bool
    {
        return $this->model->where('question_id', $id)->delete() > 0;
    }
}
