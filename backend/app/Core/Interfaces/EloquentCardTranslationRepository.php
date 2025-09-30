<?php
namespace App\Core\Interfaces;

use App\Core\Entities\CardTranslationEntity;
use App\Core\Repositories\CardTranslationRepositoryInterface;
use App\Models\CardTranslation;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentCardTranslationRepository implements CardTranslationRepositoryInterface
{
    /**
     * Convierte un modelo Eloquent CardTranslation a una Entidad CardTranslationEntity.
     */
    private function toEntity(CardTranslation $model): CardTranslationEntity
    {
        return new CardTranslationEntity(
            cardTranslationId: $model->card_translation_id,
            cardIdTranslation: $model->card_id_translation,
            languageCode: $model->language_code,
            keyPhrase: $model->key_phrase,
            audioPath: $model->audio_path,
        );
    }

    /**
     * Convierte una Entidad CardTranslationEntity a un array compatible con la creación/actualización de Eloquent.
     */
    private function toArray(CardTranslationEntity $entity): array
    {
        return [
            'card_id_translation' => $entity->cardIdTranslation,
            'language_code' => $entity->languageCode,
            'key_phrase' => $entity->keyPhrase,
            'audio_path' => $entity->audioPath,
        ];
    }

    public function getAll(): Collection
    {
        return CardTranslation::all()->map(fn($model) => $this->toEntity($model));
    }

    public function find(int $id): ?CardTranslationEntity
    {
        $model = CardTranslation::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByCardAndLang(int $cardId, string $langCode): ?CardTranslationEntity
    {
        $model = CardTranslation::where('card_id_translation', $cardId)
                                ->where('language_code', $langCode)
                                ->first();
        return $model ? $this->toEntity($model) : null;
    }
    
    public function findAllByCardId(int $cardId): Collection
    {
        return CardTranslation::where('card_id_translation', $cardId)
                              ->get()
                              ->map(fn($model) => $this->toEntity($model));
    }

    public function create(CardTranslationEntity $translation): CardTranslationEntity
    {
        $model = CardTranslation::create($this->toArray($translation));
        return $this->toEntity($model);
    }

    public function update(int $id, CardTranslationEntity $translation): CardTranslationEntity
    {
        $model = CardTranslation::find($id);
        if (!$model) {
            throw new ModelNotFoundException("Card Translation with ID $id not found.");
        }
        $model->update($this->toArray($translation));
        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return CardTranslation::destroy($id) > 0;
    }
}
