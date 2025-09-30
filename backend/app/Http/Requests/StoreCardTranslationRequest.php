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
        // Reglas para la creación (POST): card_id_translation es obligatorio y no puede ser ignorado.
        return [
            'card_id_translation' => ['required', 'integer', 'exists:cards,card_id'],
            'language_code' => ['required', 'string', 'max:5'], 
            'key_phrase' => ['required', 'string'], 
            'audio_path' => ['nullable', 'string'],
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
        ];
    }
}
