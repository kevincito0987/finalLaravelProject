<?php
namespace App\Core\Interfaces;

use App\Core\Entities\CardEntity;
use App\Core\Repositories\CardRepositoryInterface;
use App\Models\Card; // Usaremos el modelo Card para las consultas
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentCardRepository implements CardRepositoryInterface
{
    /**
     * Convierte un modelo Eloquent Card a una Entidad CardEntity, incluyendo nombres de relaciones.
     * @param Card $card Modelo Eloquent con relaciones 'category' y 'method' cargadas.
     */
    private function toEntity(Card $card): CardEntity
    {
        // Obtenemos los nombres de las relaciones, verificando que existan para evitar errores si no se cargaron.
        $categoryName = $card->category->category_name ?? null; // Asume que la columna es 'category_name'
        $methodName = $card->method->method_name ?? null;       // Asume que la columna es 'method_name'

        return new CardEntity(
            cardId: $card->card_id,
            uuid: $card->uuid,
            imagePath: $card->image_path,
            methodId: $card->method_id,
            categoryIdCard: $card->category_id_card,
            
            // Asignamos los nombres extraídos
            categoryName: $categoryName,
            methodName: $methodName,
        );
    }

    private function toArray(CardEntity $card): array
    {
        return [
            'uuid' => $card->uuid,
            'image_path' => $card->imagePath,
            'method_id' => $card->methodId,
            'category_id_card' => $card->categoryIdCard,
        ];
    }

    /**
     * Carga las tarjetas con las relaciones Category y Method.
     */
    public function getAll(): Collection
    {
        // Carga ansiosa (Eager Loading) de las relaciones para evitar N+1
        return Card::with(['category', 'method'])->get()->map(fn($card) => $this->toEntity($card));
    }

    public function find(int $id): ?CardEntity
    {
        $card = Card::with(['category', 'method'])->find($id);
        return $card ? $this->toEntity($card) : null;
    }

    public function findByUuid(string $uuid): ?CardEntity
    {
        $card = Card::with(['category', 'method'])->where('uuid', $uuid)->first();
        return $card ? $this->toEntity($card) : null;
    }

    public function create(CardEntity $card): CardEntity
    {
        $model = Card::create($this->toArray($card));
        $model->load(['category', 'method']); 
        return $this->toEntity($model);
    }

    public function update(int $id, CardEntity $card): CardEntity
    {
        $model = Card::find($id);
        if (!$model) {
            throw new ModelNotFoundException("Card with ID $id not found.");
        }
        $model->update($this->toArray($card));
        $model->load(['category', 'method']);
        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return Card::destroy($id) > 0;
    }
}