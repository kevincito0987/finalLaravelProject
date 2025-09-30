<?php

namespace App\Core\Entities;

class CardEntity
{
    public function __construct(
        public readonly ?int $cardId,
        public readonly string $uuid,
        public readonly string $imagePath,
        public readonly ?int $methodId,
        public readonly ?int $categoryIdCard,

        // Campos Opcionales para los Nombres (se usarán en la lectura/respuesta API)
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
            uuid: $data['uuid'] ?? '',
            imagePath: $data['image_path'] ?? '',
            methodId: $data['method_id'],
            categoryIdCard: $data['category_id_card'],
            
            // Los nombres deben ser mapeados si existen en el array de datos (ej. desde un join o relación eager loading)
            categoryName: $data['category_name'] ?? null,
            methodName: $data['method_name'] ?? null,
        );
    }
    
    /**
     * Convierte la entidad a un array asociativo.
     * Es útil para simplificar el Resource y la lógica en el Service/Repository.
     */
    public function toArray(): array
    {
        // Devolvemos las propiedades públicas de la entidad como un array.
        // Las claves están en camelCase para ser consistentes con la entidad, 
        // pero pueden ajustarse aquí si el Resource necesita snake_case.
        return [
            'cardId' => $this->cardId,
            'uuid' => $this->uuid,
            'imagePath' => $this->imagePath,
            'methodId' => $this->methodId,
            'categoryIdCard' => $this->categoryIdCard,
            'categoryName' => $this->categoryName,
            'methodName' => $this->methodName,
        ];
    }
}
