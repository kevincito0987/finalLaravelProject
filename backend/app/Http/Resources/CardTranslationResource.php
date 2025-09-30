<?php

namespace App\Http\Resources;

use App\Core\Entities\CardTranslationEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardTranslationResource extends JsonResource
{
    /**
     * @var CardTranslationEntity La entidad que estamos transformando
     */
    public $resource; 

    public function __construct(CardTranslationEntity $resource)
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
        // Mapeo de la Entidad a la estructura JSON deseada (camelCase)
        return [
            'translationId' => $this->resource->cardTranslationId,
            'cardId' => $this->resource->cardIdTranslation,
            'languageCode' => $this->resource->languageCode,
            'keyPhrase' => $this->resource->keyPhrase,
            'audioPath' => $this->resource->audioPath,
        ];
    }
}
