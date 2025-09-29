<?php

namespace App\Core\Entities;

class CardEntity
{
    public function __construct(
        public readonly int $cardId,
        public readonly string $uuid,
        public readonly string $imagePath,
        public readonly int $methodId,
        public readonly int $categoryIdCard,

        public readonly ?string $categoryName = null,
        public readonly ?string $methodName = null,
    ) {}

    /**
     * Crea la entidad a partir de un array de datos (usado a menudo en Repositorios).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            cardId: $data['card_id'],
            uuid: $data['uuid'], // RESTAURADO
            imagePath: $data['image_path'],
            // ELIMINADO: phrase
            // ELIMINADO: audioPath
            methodId: $data['method_id'],
            categoryIdCard: $data['category_id_card'],
        );
    }
}
