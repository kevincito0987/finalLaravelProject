<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// Importamos la interfaz o clase concreta de tu servicio de subida/almacenamiento
use App\Core\Services\MediaUploader; 

/**
 * @OA\Schema(
 * title="CardTranslationResource",
 * description="Datos detallados de una traducción de tarjeta.",
 * @OA\Xml(name="CardTranslationResource")
 * )
 */
class CardTranslationResource extends JsonResource
{
    /**
     * @var MediaUploader La instancia del servicio de almacenamiento.
     */
    protected MediaUploader $mediaUploader;

    /**
     * Inyectamos la dependencia en el constructor del Resource.
     * El contenedor de servicios de Laravel se encargará de esto.
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
        // Resuelve la dependencia del contenedor de Laravel
        $this->mediaUploader = app(MediaUploader::class); 
    }

    /**
     * Transforma el recurso en un array.
     *
     * @OA\Property(
     * property="translationId",
     * type="integer",
     * description="Identificador único de la traducción.",
     * example=123
     * )
     * @OA\Property(
     * property="cardId",
     * type="integer",
     * description="ID de la tarjeta a la que pertenece esta traducción.",
     * example=45
     * )
     * @OA\Property(
     * property="languageCode",
     * type="string",
     * description="Código ISO del idioma de la traducción (ej: 'es', 'zh').",
     * example="zh"
     * )
     * @OA\Property(
     * property="keyPhrase",
     * type="string",
     * description="La frase traducida o clave principal.",
     * example="It works"
     * )
     * @OA\Property(
     * property="audioPath",
     * type="string",
     * description="La URL pública y completa del archivo de audio TTS.",
     * example="https://bmcnrnnhyazclvwgrieb.supabase.co/storage/v1/object/public/laravel/user_2/audio/translation_68dc2c933c568.mp3"
     * )
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $audioPath = $this->resource->audioPath;
        $fullUrl = $audioPath; // Por defecto, si ya es una URL externa, la mantenemos.

        if ($audioPath && !filter_var($audioPath, FILTER_VALIDATE_URL)) {
            // Si NO es una URL válida (es decir, es una ruta interna de Supabase),
            // usamos la instancia inyectada para obtener la URL pública.
            
            // Usamos el método de la clase MediaUploader inyectada
            $fullUrl = $this->mediaUploader->getPublicUrl($audioPath); 
        }

        return [
            'translationId' => $this->resource->cardTranslationId,
            'cardId' => $this->resource->cardId,
            'languageCode' => $this->resource->languageCode,
            'keyPhrase' => $this->resource->keyPhrase,
            // AQUI RETORNAMOS LA URL PÚBLICA COMPLETA
            'audioPath' => $fullUrl, 
        ];
    }
}
