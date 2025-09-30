<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        // Requerimos la FK, el idioma y la frase clave.
        // El archivo de audio (audio_file) es opcional, ya que puede ser generado por TTS.
        return [
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
            'language_code.required' => 'El código de idioma es obligatorio.',
            'language_code.max' => 'El código de idioma no puede superar los 5 caracteres.',
            'key_phrase.required' => 'La frase clave (traducción) es obligatoria.',
            'audio_file.mimetypes' => 'El archivo debe ser un formato de audio válido (mp3, wav, ogg).',
            'audio_file.max' => 'El archivo de audio no debe superar los 5MB.',
        ];
    }
}
