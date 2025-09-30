<?php

namespace App\Core\Repositories;

use App\Core\Entities\LessonCardEntity;
use App\Core\Interfaces\LessonCardRepositoryInterface;
use App\Models\LessonCard; // Asumo la existencia de este modelo
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * Implementación del LessonCardRepositoryInterface que utiliza Eloquent ORM.
 */
class LessonCardRepository implements LessonCardRepositoryInterface
{
    /**
     * @var LessonCard El modelo de Eloquent para interactuar con la tabla pivote.
     */
    protected LessonCard $model;

    public function __construct(LessonCard $model)
    {
        $this->model = $model;
    }

    /**
     * Convierte un Modelo de Eloquent (o Builder) a una Entidad de Dominio.
     *
     * @param LessonCardModel|Builder $model
     * @return LessonCardEntity
     */
    private function toEntity($model): LessonCardEntity
    {
        return new LessonCardEntity(
            lessonId: $model->lesson_id,
            cardId: $model->card_id,
            orderInLesson: $model->order_in_lesson,
        );
    }

    /**
     * Busca la asociación por clave compuesta.
     *
     * @param int $lessonId ID de la Lección.
     * @param int $cardId ID de la Tarjeta.
     * @return LessonCardModel|null
     */
    private function findModelByKeys(int $lessonId, int $cardId): ?LessonCard
    {
        return $this->model->newQuery()
            ->where('lesson_id', $lessonId)
            ->where('card_id', $cardId)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function save(LessonCardEntity $entity): LessonCardEntity
    {
        // Usamos updateOrCreate ya que LessonCard es una tabla pivote que usa una clave compuesta.
        $model = $this->model->newQuery()->updateOrCreate(
            [
                'lesson_id' => $entity->lessonId,
                'card_id' => $entity->cardId,
            ],
            [
                'order_in_lesson' => $entity->orderInLesson,
            ]
        );

        return $this->toEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function findByKeys(int $lessonId, int $cardId): ?LessonCardEntity
    {
        $model = $this->findModelByKeys($lessonId, $cardId);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $lessonId, int $cardId): bool
    {
        $model = $this->findModelByKeys($lessonId, $cardId);

        if (!$model) {
            return false;
        }

        return (bool) $model->delete();
    }

    /**
     * @inheritDoc
     */
    public function getCardsByLessonId(int $lessonId): Collection
    {
        /** @var EloquentCollection<LessonCardModel> $models */
        $models = $this->model->newQuery()
            ->where('lesson_id', $lessonId)
            ->orderBy('order_in_lesson', 'asc')
            ->get();

        // Mapea la colección de Modelos a una colección de Entidades
        return $models->map(fn($model) => $this->toEntity($model));
    }

    /**
     * @inheritDoc
     */
    public function updateOrder(int $lessonId, int $cardId, int $newOrder): bool
    {
        $updatedRows = $this->model->newQuery()
            ->where('lesson_id', $lessonId)
            ->where('card_id', $cardId)
            ->update(['order_in_lesson' => $newOrder]);

        return $updatedRows > 0;
    }
}
