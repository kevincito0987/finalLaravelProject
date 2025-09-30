<?php

namespace App\Http\Resources;

use App\Core\Entities\CardTranslationEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * schema="CardTranslationResource",
 * title="Card Translation Resource",
 * description="Estructura de datos para la traducción de una tarjeta, incluyendo la ruta del archivo de audio TTS/subido.",
 * @OA\Property(property="translationId", type="integer", description="ID único de la traducción.", example=1),
 * @OA\Property(property="cardId", type="integer", description="ID de la tarjeta a la que pertenece esta traducción.", example=10),
 * @OA\Property(property="languageCode", type="string", description="Código del idioma de la traducción (e.g., 'en', 'es').", example="en"),
 * @OA\Property(property="keyPhrase", type="string", description="La frase clave traducida (el texto que se reproduce).", example="Hello, world"),
 * @OA\Property(property="audioPath", type="string", description="Ruta del archivo de audio generado por TTS o subido.", example="user_1/audio/translation_abc123.mp3"),
 * )
 */
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
