<?php

namespace App\Http\Resources;

use App\Core\Entities\CardEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * @var CardEntity La entidad que estamos transformando
     */
    public $resource; 

    public function __construct(CardEntity $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transforma la entidad en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Mapeo de Entidad a JSON de salida
            'cardId' => $this->resource->cardId, 
            'uuid' => $this->resource->uuid,
            'imagePath' => $this->resource->imagePath,
            
            // IDs de Foráneas
            'methodId' => $this->resource->methodId,
            'categoryIdCard' => $this->resource->categoryIdCard,
            
            // Nombres de Relaciones (NUEVOS CAMPOS)
            'categoryName' => $this->resource->categoryName,
            'methodName' => $this->resource->methodName,
        ];
    }
}