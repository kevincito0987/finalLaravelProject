<?php

namespace App\Core\Repositories;

use App\Core\Entities\Card\CardTranslationEntity;
use App\Core\Interfaces\CardTranslationRepositoryInterface;
use App\Models\CardTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Implementación del repositorio de traducciones de tarjetas usando Eloquent.
 * Asegura el mapeo entre las Entidades de Core y los Modelos de Laravel.
 */
class EloquentCardTranslationRepository implements CardTranslationRepositoryInterface
{
    protected CardTranslation $model;

    public function __construct(CardTranslation $model)
    {
        $this->model = $model;
    }

    /**
     * Convierte la entidad en un array para Eloquent, mapeando los nombres de la Entidad a la DB.
     * @param CardTranslationEntity $entity
     * @return array
     */
    protected function toModelData(CardTranslationEntity $entity): array
    {
        return [
            // Mapeo crucial: Entity.cardId (camelCase) -> DB.card_id_translation (snake_case)
            'card_id_translation' => $entity->cardId, 
            'language_code' => $entity->languageCode,
            'key_phrase' => $entity->keyPhrase,
            'audio_path' => $entity->audioPath, // Simplemente guardamos el string que nos pase el servicio
        ];
    }

    public function create(CardTranslationEntity $entity): CardTranslationEntity
    {
        // El método create recibe el array mapeado a las columnas de la BD
        $model = $this->model->create($this->toModelData($entity));
        // Se mapea el modelo de vuelta a la Entidad (incluyendo el ID generado)
        return CardTranslationEntity::fromRepository($model->toArray());
    }

    public function find(int $id): ?CardTranslationEntity
    {
        $model = $this->model->find($id);
        return $model ? CardTranslationEntity::fromRepository($model->toArray()) : null;
    }

    public function getByCardId(int $cardId): Collection
    {
        // Se usa 'card_id_translation' en la consulta SQL, que es el nombre de la columna FK
        return $this->model->where('card_id_translation', $cardId)
                            ->get()
                            // Mapea la colección de modelos a una colección de entidades
                            ->map(fn ($model) => CardTranslationEntity::fromRepository($model->toArray()));
    }
    
    public function getAll(): Collection
    {
        return $this->model->all()
                            ->map(fn ($model) => CardTranslationEntity::fromRepository($model->toArray()));
    }

    public function update(int $id, CardTranslationEntity $entity): CardTranslationEntity
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new ModelNotFoundException("CardTranslation con ID {$id} no encontrada para actualizar.");
        }
        
        // El método update recibe el array mapeado a las columnas de la BD
        $model->update($this->toModelData($entity));
        
        // Retorna la Entidad con los datos actualizados
        return CardTranslationEntity::fromRepository($model->toArray());
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
