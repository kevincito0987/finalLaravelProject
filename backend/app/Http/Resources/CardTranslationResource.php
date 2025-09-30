<?php

namespace App\Http\Resources;

use App\Core\Entities\CardTranslationEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardTranslationResource extends JsonResource
{
    /**
     * @var CardTranslationEntity La entidad que estamos transformando
     * Se define el tipo explícitamente para ayudar en el autocompletado y tipado.
     */
    public $resource; 

    public function __construct(CardTranslationEntity $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transforma la entidad en un array, aplicando la convención camelCase.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Mapeo de la Entidad a la estructura JSON deseada (ej: translationId)
        return [
            // Renombramos la PK
            'translationId' => $this->resource->cardTranslationId,
            
            // CORRECCIÓN DE LA PROPIEDAD: Asumimos que la FK se llama 'cardId' en la Entidad.
            'cardId' => $this->resource->cardId,
            
            // Mantenemos los nombres que ya están en camelCase o son claros
            'languageCode' => $this->resource->languageCode,
            'keyPhrase' => $this->resource->keyPhrase,
            'audioPath' => $this->resource->audioPath,
        ];
    }
}
