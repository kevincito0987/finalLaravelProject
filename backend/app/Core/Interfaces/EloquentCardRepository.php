<?php
namespace App\Core\Interfaces;

use App\Core\Entities\CardEntity;
use App\Core\Repositories\CardRepositoryInterface;
use App\Models\Card;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Schema;

/**
 * Implementación del Repositorio de Tarjetas usando Eloquent.
 * Mapea el Modelo Eloquent a la Entidad de Dominio (CardEntity).
 * No utiliza la columna 'position' para el ordenamiento ni reordenamiento.
 * El índice consecutivo (1, 2, 3...) se calcula en la capa de Servicio.
 */
class EloquentCardRepository implements CardRepositoryInterface
{
    /**
     * Convierte un modelo Eloquent Card a una Entidad CardEntity.
     */
    private function toEntity(Card $card): CardEntity
    {
        return new CardEntity(
            cardId: $card->card_id,
            uuid: $card->uuid,
            imagePath: $card->image_path,
            methodId: $card->method_id,
            categoryIdCard: $card->category_id_card,
            
            // Mapeamos los nombres de las relaciones.
            categoryName: $card->category?->category_name,
            methodName: $card->method?->method_name,
        );
    }

    /**
     * Convierte una Entidad CardEntity a un array compatible con la creación/actualización de Eloquent.
     */
    private function toArray(CardEntity $card): array
    {
        // Solo incluimos campos que existen en la tabla 'cards' sin la columna 'position'.
        $data = [
            'uuid' => $card->uuid,
            'image_path' => $card->imagePath,
            'method_id' => $card->methodId,
            'category_id_card' => $card->categoryIdCard,
        ];

        return $data;
    }

    /**
     * Obtiene todas las tarjetas cargando las relaciones y ordenando por ID.
     */
    public function getAll(): Collection
    {
        // CARGA CLAVE: Usamos 'with' para traer los datos de las categorías y métodos.
        // Ordenamos por card_id (aunque no sea consecutivo), ya que es el orden natural de creación.
        $query = Card::with(['category', 'method'])
                     ->orderBy('card_id', 'asc');

        // El repositorio solo devuelve la Entidad. La numeración consecutivo se hace en el Servicio.
        return $query->get()->map(fn($card) => $this->toEntity($card));
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
        
        $updateData = $this->toArray($card);

        // Filtramos para evitar actualizar campos con valores nulos o vacíos.
        $filteredData = array_filter($updateData, fn($value) => $value !== null && $value !== '');

        if (empty($filteredData)) {
            $model->load(['category', 'method']);
            return $this->toEntity($model);
        }

        $model->update($filteredData);
        
        $model->load(['category', 'method']);
        return $this->toEntity($model);
    }

    /**
     * Elimina una tarjeta. No hay reordenamiento de IDs.
     */
    public function delete(int $id): bool
    {
        // Simple eliminación. El Service se encarga de reestructurar la numeración
        // al momento de obtener la lista nuevamente.
        return Card::destroy($id) > 0;
    }
}
