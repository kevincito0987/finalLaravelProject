<?php

namespace App\Core\Entities;

use JsonSerializable;

/**
 * Entidad de la traducción de una tarjeta (CardTranslation).
 */
class CardTranslationEntity implements JsonSerializable
{
    // Trait que asumiremos que tienes para el método toArray() y jsonSerialize()
    // Si no lo tienes, puedes añadir los métodos toArray y jsonSerialize manualmente.
    // use App\Core\Traits\EntityToArray;

    public function __construct(
        public ?int $cardTranslationId, // PK
        public int $cardId, // FK a cards (card_id) - Usamos cardId para la Entidad
        public string $languageCode, // Ej: 'es', 'en'
        public string $keyPhrase,
        public ?string $audioPath, // URL o Path en Supabase
    ) {}

    /**
     * Retorna una nueva instancia a partir de datos del repositorio (array o modelo).
     *
     * IMPORTANTE: Aquí mapeamos los nombres de la BD ('card_id_translation') a los nombres de la Entidad ('cardId').
     * @param array|object $data
     * @return self
     */
    public static function fromRepository($data): self
    {
        $data = (array) $data;
        return new self(
            cardTranslationId: $data['card_translation_id'] ?? null,
            cardId: $data['card_id_translation'], // <-- Mapea la columna FK de la BD
            languageCode: $data['language_code'],
            keyPhrase: $data['key_phrase'],
            audioPath: $data['audio_path'] ?? null,
        );
    }

    /**
     * Convierte la entidad en un array para ser usado por el Repositorio o Resource.
     * Si no tienes el Trait EntityToArray, necesitas este método.
     */
    public function toArray(): array
    {
        return [
            'cardTranslationId' => $this->cardTranslationId,
            'cardId' => $this->cardId,
            'languageCode' => $this->languageCode,
            'keyPhrase' => $this->keyPhrase,
            'audioPath' => $this->audioPath,
        ];
    }
    
    // Implementación requerida por JsonSerializable
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
