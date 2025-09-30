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
        // Reglas para la actualización (PUT/PATCH).
        // Hacemos todas las reglas 'sometimes' si es PATCH, o simplemente permiten ser opcionales si ya están en la URL.
        
        // Nota: Para PUT/PATCH, 'key_phrase' y 'language_code' se deben enviar, pero no son obligatorios si no se quieren cambiar.
        // Aquí los hacemos 'sometimes' para permitir actualizaciones parciales (PATCH).
        
        return [
            // card_id_translation es opcional en la actualización.
            'card_id_translation' => ['sometimes', 'integer', 'exists:cards,card_id'],
            
            // language_code y key_phrase son requeridos si se envían (sometimes)
            'language_code' => ['sometimes', 'string', 'max:5'], 
            'key_phrase' => ['sometimes', 'string'], 
            'audio_path' => ['nullable', 'string'],
        ];
    }
    
    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'card_id_translation.exists' => 'El ID de la tarjeta proporcionado no existe.',
            'language_code.max' => 'El código de idioma no puede superar los 5 caracteres.',
        ];
    }
}
