<?php
namespace App\Core\Services;

use App\Core\Entities\CardEntity;
use App\Core\Repositories\CardRepositoryInterface;
use Illuminate\Support\Collection;

class CardService
{
    protected CardRepositoryInterface $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * Recupera una colección de todas las tarjetas y les asigna un índice consecutivo
     * para propósitos de visualización en el frontend (ej: 1, 2, 3...).
     * @return Collection<CardEntity|array> Retorna una colección de Entidades o arrays con el índice.
     */
    public function getCards(): Collection
    {
        // 1. Obtener la colección de Entidades del Repositorio (ordenadas por card_id, ej: 3, 4, 7, 9...)
        $cards = $this->cardRepository->getAll();

        // 2. Mapear la colección para agregar el campo 'consecutiveId'.
        // Usamos map con el índice ($key) que es 0, 1, 2, 3...
        return $cards->map(function (CardEntity $card, int $key) {
            
            // Convertimos la Entidad a un array para poder agregarle el nuevo campo.
            // Si tu Entidad tiene un método toArray(), úsalo; de lo contrario, 
            // creamos el array manualmente a partir de las propiedades públicas.
            
            return [
                'cardId' => $card->cardId,
                'uuid' => $card->uuid,
                'imagePath' => $card->imagePath,
                'methodId' => $card->methodId,
                'categoryIdCard' => $card->categoryIdCard,
                'categoryName' => $card->categoryName,
                'methodName' => $card->methodName,
                
                // CAMBIO CLAVE: Asignamos un ID consecutivo que empieza en 1.
                'consecutiveId' => $key + 1,
            ];
        });
    }

    /**
     * Recupera una tarjeta por su ID (PK).
     * @param int $id El ID primario de la tarjeta.
     * @return ?CardEntity
     */
    public function getCard(int $id): ?CardEntity
    {
        return $this->cardRepository->find($id);
    }
    
    /**
     * Recupera una tarjeta por su UUID único.
     * @param string $uuid El identificador único de la tarjeta.
     * @return ?CardEntity
     */
    public function getCardByUuid(string $uuid): ?CardEntity
    {
        return $this->cardRepository->findByUuid($uuid);
    }
    
    /**
     * Crea una nueva tarjeta.
     * @param CardEntity $card La entidad de tarjeta a crear.
     * @return CardEntity
     */
    public function createCard(CardEntity $card): CardEntity
    {
        // Se podría añadir lógica de negocio aquí, como generar el UUID si no viene.
        return $this->cardRepository->create($card);
    }

    /**
     * Actualiza una tarjeta existente por su ID.
     * @param int $id El ID de la tarjeta a actualizar.
     * @param CardEntity $card La entidad con los datos actualizados (parciales o completos).
     * @return CardEntity
     */
    public function updateCard(int $id, CardEntity $card): CardEntity
    {
        return $this->cardRepository->update($id, $card);
    }

    /**
     * Elimina una tarjeta por su ID.
     * @param int $id El ID de la tarjeta a eliminar.
     * @return bool
     */
    public function deleteCard(int $id): bool
    {
        return $this->cardRepository->delete($id);
    }
}
