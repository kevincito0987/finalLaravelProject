<?php
namespace App\Core\Services;

use App\Core\Entities\CardTranslationEntity;
use App\Core\Repositories\CardTranslationRepositoryInterface;
use Illuminate\Support\Collection;

class CardTranslationService
{
    protected CardTranslationRepositoryInterface $repository;

    public function __construct(CardTranslationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Recupera una colección de todas las traducciones.
     * @return Collection<CardTranslationEntity>
     */
    public function getAllTranslations(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Recupera una traducción por su ID (PK).
     * @param int $id
     * @return ?CardTranslationEntity
     */
    public function getTranslation(int $id): ?CardTranslationEntity
    {
        return $this->repository->find($id);
    }

    /**
     * Busca una traducción por ID de Tarjeta y código de idioma.
     * @param int $cardId
     * @param string $langCode
     * @return ?CardTranslationEntity
     */
    public function getTranslationByCardAndLang(int $cardId, string $langCode): ?CardTranslationEntity
    {
        // Lógica de validación o cacheo aquí si es necesaria
        return $this->repository->findByCardAndLang($cardId, $langCode);
    }
    
    /**
     * Obtiene todas las traducciones para una tarjeta específica.
     * @param int $cardId
     * @return Collection<CardTranslationEntity>
     */
    public function getAllTranslationsForCard(int $cardId): Collection
    {
        return $this->repository->findAllByCardId($cardId);
    }

    /**
     * Crea una nueva traducción.
     * @param CardTranslationEntity $translation
     * @return CardTranslationEntity
     */
    public function createTranslation(CardTranslationEntity $translation): CardTranslationEntity
    {
        // Lógica de negocio (ej. asegurar que el languageCode sea válido)
        return $this->repository->create($translation);
    }

    /**
     * Actualiza una traducción existente por su ID.
     * @param int $id
     * @param CardTranslationEntity $translation
     * @return CardTranslationEntity
     */
    public function updateTranslation(int $id, CardTranslationEntity $translation): CardTranslationEntity
    {
        return $this->repository->update($id, $translation);
    }

    /**
     * Elimina una traducción por su ID.
     * @param int $id
     * @return bool
     */
    public function deleteTranslation(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
