<?php

namespace App\Core\Interfaces;

use App\Core\Entities\Card\CardTranslationEntity;
use Illuminate\Support\Collection;

/**
 * Define las operaciones de acceso a datos (CRUD) para la entidad CardTranslation.
 */
interface CardTranslationRepositoryInterface
{
    /**
     * Crea una nueva traducción para una tarjeta.
     * @param CardTranslationEntity $entity La entidad de la traducción a persistir.
     * @return CardTranslationEntity
     */
    public function create(CardTranslationEntity $entity): CardTranslationEntity;

    /**
     * Busca una traducción por su ID primario.
     * @param int $id El ID primario de la traducción.
     * @return ?CardTranslationEntity
     */
    public function find(int $id): ?CardTranslationEntity;

    /**
     * Obtiene todas las traducciones de una tarjeta específica.
     * @param int $cardId El ID de la tarjeta padre.
     * @return Collection<CardTranslationEntity>
     */
    public function getByCardId(int $cardId): Collection;

    /**
     * Obtiene todas las traducciones de la base de datos.
     * @return Collection<CardTranslationEntity>
     */
    public function getAll(): Collection;

    /**
     * Actualiza una traducción existente.
     * @param int $id El ID primario de la traducción a actualizar.
     * @param CardTranslationEntity $entity La entidad con los datos actualizados.
     * @return CardTranslationEntity
     */
    public function update(int $id, CardTranslationEntity $entity): CardTranslationEntity;

    /**
     * Elimina una traducción por su ID.
     * @param int $id El ID primario de la traducción a eliminar.
     * @return bool
     */
    public function delete(int $id): bool;
}
