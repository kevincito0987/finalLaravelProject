<?php
namespace App\Core\Repositories;

use App\Core\Entities\CardTranslationEntity;
use Illuminate\Support\Collection;

interface CardTranslationRepositoryInterface
{
    /**
     * Obtiene todas las traducciones.
     * @return Collection<CardTranslationEntity>
     */
    public function getAll(): Collection;

    /**
     * Encuentra una traducción por su clave primaria (card_translation_id).
     * @param int $id
     * @return ?CardTranslationEntity
     */
    public function find(int $id): ?CardTranslationEntity;

    /**
     * Encuentra una traducción específica por ID de tarjeta y código de idioma.
     * @param int $cardId
     * @param string $langCode
     * @return ?CardTranslationEntity
     */
    public function findByCardAndLang(int $cardId, string $langCode): ?CardTranslationEntity;
    
    /**
     * Obtiene todas las traducciones para una tarjeta específica.
     * @param int $cardId
     * @return Collection<CardTranslationEntity>
     */
    public function findAllByCardId(int $cardId): Collection;

    /**
     * Crea una nueva traducción.
     * @param CardTranslationEntity $translation
     * @return CardTranslationEntity
     */
    public function create(CardTranslationEntity $translation): CardTranslationEntity;

    /**
     * Actualiza una traducción existente.
     * @param int $id
     * @param CardTranslationEntity $translation
     * @return CardTranslationEntity
     */
    public function update(int $id, CardTranslationEntity $translation): CardTranslationEntity;

    /**
     * Elimina una traducción por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
