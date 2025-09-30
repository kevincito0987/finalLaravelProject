<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * schema="StoreCardTranslationRequest",
 * title="Crear Traducción de Tarjeta",
 * description="Datos requeridos para crear una nueva traducción. El ID de la tarjeta principal (FK) se asume que se obtiene de la ruta de la API. Se espera 'audio_file' O 'audio_url' o ninguno (para forzar TTS).",
 * required={"language_code", "key_phrase"},
 * @OA\Property(
 * property="language_code",
 * type="string",
 * maxLength=10,
 * description="Código del idioma de la traducción (e.g., 'en', 'fr').",
 * example="en"
 * ),
 * @OA\Property(
 * property="key_phrase",
 * type="string",
 * maxLength=500,
 * description="La frase clave traducida.",
 * example="Hello, nice to meet you"
 * ),
 * @OA\Property(
 * property="audio_file",
 * type="string",
 * format="binary",
 * nullable=true,
 * description="Archivo de audio a subir (MP3/WAV/OGG), máx 5MB. Prohíbe el uso de 'audio_url'."
 * ),
 * @OA\Property(
 * property="audio_url",
 * type="string",
 * format="url",
 * nullable=true,
 * maxLength=2048,
 * description="URL externa del archivo de audio. Prohíbe el uso de 'audio_file'."
 * )
 * )
 * Define las reglas de validación para crear una nueva traducción de tarjeta.
 */
class StoreCardTranslationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     */
    public function rules(): array
    {
        return [
            // ESTE CAMPO DEBE SER REQUERIDO, ENTERO Y EXISTIR EN LA TABLA 'CARDS'
            'card_id_translation' => ['required', 'integer', 'exists:cards,card_id'], 
            'language_code' => ['required', 'string', 'max:10'],
            'key_phrase' => ['required', 'string', 'max:500'],

            // Campos de audio (mutuamente excluyentes)
            'audio_file' => [
                'nullable', 
                'file',
                'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp3',
                'max:5120',
                'prohibits:audio_url'
            ], 
            
            'audio_url' => [
                'nullable', 
                'url',
                'max:2048',
                'prohibits:audio_file'
            ], 
        ];
    }

    /**
     * Prepara los datos para la validación.
     * Esto es opcional, pero ayuda a asegurar que el campo esté disponible.
     */
    protected function prepareForValidation()
    {
        // Si el cliente envía el ID como 'cardId' o 'card_id', puedes normalizarlo
        if ($this->has('cardId') && !$this->has('card_id_translation')) {
            $this->merge([
                'card_id_translation' => $this->input('cardId'),
            ]);
        }
    }
}
