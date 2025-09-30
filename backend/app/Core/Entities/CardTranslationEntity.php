<?php

namespace App\Core\Entities;

class CardTranslationEntity
{
    public function __construct(
        public readonly ?int $cardTranslationId, // Puede ser nulo al crear
        public readonly int $cardIdTranslation,
        public readonly string $languageCode,
        public readonly string $keyPhrase,
        public readonly ?string $audioPath, // Puede ser nulo
    ) {}

    /**
     * Crea la entidad a partir de un array de datos (usado a menudo en Repositorios).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            cardTranslationId: $data['card_translation_id'] ?? null,
            cardIdTranslation: $data['card_id_translation'],
            languageCode: $data['language_code'],
            keyPhrase: $data['key_phrase'],
            audioPath: $data['audio_path'] ?? null,
        );
    }
}
