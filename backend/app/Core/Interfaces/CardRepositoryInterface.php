<?php

namespace App\Core\Interfaces;

use App\Core\Entities\Card\CardEntity;
use Illuminate\Support\Collection;

interface CardRepositoryInterface
{
    /**
     * Obtiene una colección de todas las tarjetas.
     * @return Collection<CardEntity>
     */
    public function getAll(): Collection;

    /**
     * Encuentra una tarjeta por su clave primaria (card_id).
     * @param int $id
     * @return ?CardEntity
     */
    public function find(int $id): ?CardEntity;

    /**
     * Encuentra una tarjeta por su UUID único.
     * @param string $uuid
     * @return ?CardEntity
     */
    public function findByUuid(string $uuid): ?CardEntity;
    
    /**
     * Crea una nueva tarjeta.
     * @param CardEntity $card
     * @return CardEntity
     */
    public function create(CardEntity $card): CardEntity;

    /**
     * Actualiza una tarjeta existente por su ID.
     * @param int $id
     * @param CardEntity $card
     * @return CardEntity
     */
    public function update(int $id, CardEntity $card): CardEntity;

    /**
     * Elimina una tarjeta por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
