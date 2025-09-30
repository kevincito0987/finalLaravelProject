<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardTranslationRequest extends FormRequest
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
        // Reglas para la actualización (PUT/PATCH). Todos los campos usan 'sometimes' 
        // para permitir actualizaciones parciales (opcionales, pero validados si están presentes).
        
        return [
            // NOTA: Se ha eliminado 'card_id_translation' ya que el ID de la tarjeta 
            // no debe cambiar en una actualización y se obtiene de la traducción existente.
            
            'language_code' => ['sometimes', 'string', 'max:5'], 
            'key_phrase' => ['sometimes', 'string'], 
            
            // Campo específico para la subida de archivos de audio
            'audio_file' => [
                'sometimes', // Solo valida si está presente en la petición
                'nullable', 
                'file', 
                'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp3',
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
            // Se ha eliminado el mensaje de error para 'card_id_translation.exists'
            'language_code.max' => 'El código de idioma no puede superar los 5 caracteres.',
            'audio_file.mimetypes' => 'El archivo debe ser un formato de audio válido (mp3, wav, ogg).',
            'audio_file.max' => 'El archivo de audio no debe superar los 5MB.',
        ];
    }
}
