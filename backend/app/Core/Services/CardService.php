<?php
namespace App\Core\Services;

use App\Core\Entities\CardEntity;
use App\Core\Repositories\CardRepositoryInterface;
use Illuminate\Support\Collection;

class CardService
{
    protected $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * Recupera una colección de todas las tarjetas.
     * @return Collection<CardEntity>
     */
    public function getCards(): Collection
    {
        return $this->cardRepository->getAll();
    }

    /**
     * Recupera una tarjeta por su ID (PK).
     * @param int $id
     * @return ?CardEntity
     */
    public function getCard(int $id): ?CardEntity
    {
        return $this->cardRepository->find($id);
    }
    
    /**
     * Recupera una tarjeta por su UUID.
     * @param string $uuid
     * @return ?CardEntity
     */
    public function getCardByUuid(string $uuid): ?CardEntity
    {
        // Lógica de negocio si es necesaria (ej. permisos, caching)
        return $this->cardRepository->findByUuid($uuid);
    }
    
    /**
     * Crea una nueva tarjeta.
     * @param CardEntity $card
     * @return CardEntity
     */
    public function createCard(CardEntity $card): CardEntity
    {
        // Lógica de negocio (ej. validaciones, procesamiento de imagen antes de guardar)
        return $this->cardRepository->create($card);
    }

    /**
     * Actualiza una tarjeta existente por su ID.
     * @param int $id
     * @param CardEntity $card
     * @return CardEntity
     */
    public function updateCard(int $id, CardEntity $card): CardEntity
    {
        return $this->cardRepository->update($id, $card);
    }

    /**
     * Elimina una tarjeta por su ID.
     * @param int $id
     * @return bool
     */
    public function deleteCard(int $id): bool
    {
        return $this->cardRepository->delete($id);
    }
}