<?php

namespace App\Repositories;

use App\Core\Interfaces\UserLessonRepositoryInterface;
use App\Core\Entities\UserLessonEntity;
use App\Core\Entities\LessonEntity; // Asegúrate de que esta entidad existe
use App\Models\UserLesson;
use App\Models\Lesson; // Modelo de Eloquent de la Lección
use Illuminate\Database\Eloquent\Model; // Para tipar el método privado
use Illuminate\Support\Collection;
use DateTime;

/**
 * Implementación del Repositorio de UserLesson usando Eloquent.
 * Realiza la traducción entre Modelos de Eloquent y Entidades de Dominio.
 */
class EloquentUserLessonRepository implements UserLessonRepositoryInterface
{
    public function __construct(
        protected UserLesson $userLessonModel,
        protected Lesson $lessonModel
    ) {}

    /**
     * Convierte un Modelo de Eloquent Lesson a una Entidad de Dominio LessonEntity.
     * Esta función actúa como el Mapper/Traductor.
     *
     * @param Lesson $model
     * @return LessonEntity
     */
    private function mapLessonModelToEntity(Lesson $model): LessonEntity
    {
        // Usamos el constructor de LessonEntity con la data del modelo de Eloquent.
        return new LessonEntity(
            lessonName: $model->lesson_name, // Asume columna 'lesson_name'
            description: $model->description,
            lessonType: $model->lesson_type, // Asume columna 'lesson_type'
            lessonId: $model->lesson_id // Asume clave primaria 'lesson_id'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function markCompleted(int $userId, int $lessonId): UserLessonEntity
    {
        // El método updateOrCreate es eficiente para este tipo de tabla pivote
        $model = $this->userLessonModel->updateOrCreate(
            [
                'user_id_lesson' => $userId,
                'lesson_id_lesson' => $lessonId,
            ],
            [
                'completed_at' => new DateTime(), // Marca el tiempo actual
            ]
        );

        // Convertir el Modelo Eloquent a Entidad de Dominio UserLessonEntity
        return new UserLessonEntity(
            userId: $model->user_id_lesson,
            lessonId: $model->lesson_id_lesson,
            completedAt: $model->completed_at,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function findByKeys(int $userId, int $lessonId): ?UserLessonEntity
    {
        $model = $this->userLessonModel
            ->where('user_id_lesson', $userId)
            ->where('lesson_id_lesson', $lessonId)
            ->first();

        if (!$model) {
            return null;
        }

        // Convertir Modelo a Entidad
        return new UserLessonEntity(
            userId: $model->user_id_lesson,
            lessonId: $model->lesson_id_lesson,
            completedAt: $model->completed_at,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCompletedLessonsByUserId(int $userId): Collection
    {
        // 1. Obtener los IDs de las lecciones completadas
        $completedLessonIds = $this->userLessonModel
            ->where('user_id_lesson', $userId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id_lesson');

        // 2. Obtener los modelos de Lección correspondientes
        $lessonModels = $this->lessonModel
            ->whereIn($this->lessonModel->getKeyName(), $completedLessonIds)
            ->get();

        // 3. Convertir Modelos de Lección a LessonEntity usando el método local de mapeo
        return $lessonModels->map(function (Lesson $lessonModel) {
            return $this->mapLessonModelToEntity($lessonModel);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getPendingLessonsByUserId(int $userId): Collection
    {
        // 1. Obtener los IDs de las lecciones YA completadas
        $completedLessonIds = $this->userLessonModel
            ->where('user_id_lesson', $userId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id_lesson');

        // 2. Obtener TODAS las lecciones que NO están en la lista de completadas
        $pendingLessonModels = $this->lessonModel
            ->whereNotIn($this->lessonModel->getKeyName(), $completedLessonIds)
            ->get();

        // 3. Convertir Modelos de Lección a LessonEntity usando el método local de mapeo
        return $pendingLessonModels->map(function (Lesson $lessonModel) {
            return $this->mapLessonModelToEntity($lessonModel);
        });
    }
}
