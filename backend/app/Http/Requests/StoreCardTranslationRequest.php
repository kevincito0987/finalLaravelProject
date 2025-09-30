<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * schema="StoreCardTranslationRequest",
 * title="Store Card Translation Request",
 * description="Datos necesarios para crear una nueva traducción de tarjeta (incluyendo opcionalmente la subida de un archivo de audio).",
 * required={"card_id_translation", "language_code", "key_phrase"},
 * @OA\Property(
 * property="card_id_translation",
 * type="integer",
 * description="ID de la tarjeta a la que pertenece esta traducción (Foreign Key).",
 * example=101
 * ),
 * @OA\Property(
 * property="language_code",
 * type="string",
 * description="Código de idioma de la traducción (ej: es, en, fr).",
 * example="es"
 * ),
 * @OA\Property(
 * property="key_phrase",
 * type="string",
 * description="La frase clave o palabra de la traducción.",
 * example="Yo quiero agua"
 * ),
 * @OA\Property(
 * property="audio_file",
 * type="string",
 * format="binary",
 * description="Archivo de audio opcional (mp3, wav, ogg). Si no se proporciona, se generará audio por TTS.",
 * nullable=true
 * )
 * )
 */
class StoreCardTranslationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // La autorización se maneja a nivel de Policy.
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     */
    public function rules(): array
    {
        // Nota: Agregué la validación para 'card_id_translation' ya que es
        // una FK requerida en la lógica del Controller y la Entidad.
        return [
            'card_id_translation' => ['required', 'integer', 'exists:cards,card_id'],
            'language_code' => ['required', 'string', 'max:5'], 
            'key_phrase' => ['required', 'string'], 
            
            // Campo específico para la subida de archivos de audio
            'audio_file' => [
                'nullable', 
                'file', // Debe ser un archivo
                'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp3', // Tipos de audio permitidos
                'max:5120', // 5MB máximo
            ], 
        ];
    }
    
    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'card_id_translation.required' => 'El ID de la tarjeta es obligatorio.',
            'card_id_translation.exists' => 'El ID de la tarjeta proporcionado no existe.',
            'language_code.required' => 'El código de idioma es obligatorio.',
            'language_code.max' => 'El código de idioma no puede superar los 5 caracteres.',
            'key_phrase.required' => 'La frase clave (traducción) es obligatoria.',
            'audio_file.mimetypes' => 'El archivo debe ser un formato de audio válido (mp3, wav, ogg).',
            'audio_file.max' => 'El archivo de audio no debe superar los 5MB.',
        ];
    }
}
