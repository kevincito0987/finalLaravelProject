<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * schema="UpdateCardTranslationRequest",
 * title="Update Card Translation Request",
 * description="Datos opcionales para actualizar una traducción de tarjeta. Permite actualizar la frase clave, subir un nuevo archivo de audio o proporcionar una URL de audio externa.",
 * @OA\Property(
 * property="language_code",
 * type="string",
 * description="Código de idioma de la traducción (ej: es, en, fr). Opcional.",
 * example="en",
 * nullable=true
 * ),
 * @OA\Property(
 * property="key_phrase",
 * type="string",
 * description="La nueva frase clave o palabra de la traducción. Opcional.",
 * example="I want water",
 * nullable=true
 * ),
 * @OA\Property(
 * property="audio_url",
 * type="string",
 * format="url",
 * description="URL de audio externa (ej. de S3, Cloudflare, etc.) para usar como fuente de audio. Opcional.",
 * example="https://mycdn.com/audio/water.mp3",
 * nullable=true
 * ),
 * @OA\Property(
 * property="audio_file",
 * type="string",
 * format="binary",
 * description="Nuevo archivo de audio opcional para reemplazar el existente (solo si se usa multipart/form-data).",
 * nullable=true
 * )
 * )
 */
class UpdateCardTranslationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // La autorización se maneja a nivel de Policy o Middleware.
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     */
    public function rules(): array
    {
        // Todos los campos usan 'sometimes' para permitir actualizaciones parciales (PATCH).
        
        return [
            'language_code' => ['sometimes', 'string', 'max:5'], 
            'key_phrase' => ['sometimes', 'string'], 
            
            // Nuevo campo para URL de audio
            'audio_url' => ['sometimes', 'nullable', 'url', 'max:500'],
            
            // Campo para la subida de archivos de audio
            'audio_file' => [
                // 'sometimes' y 'nullable' son cruciales para permitir el uso de 'audio_url' solo
                'sometimes', 
                'nullable', 
                'file', 
                'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp3',
                'max:5120', // 5MB máximo
            ],

            // Se añade una regla condicional para asegurar que no se envíen ambos a la vez (opcional)
            // 'audio_url' y 'audio_file' no deben estar presentes al mismo tiempo, o solo uno debe tener valor.
            'audio_file' => [
                'sometimes',
                'nullable',
                // ... (reglas de archivo) ...
                function ($attribute, $value, $fail) {
                    if ($this->has('audio_url') && $this->file('audio_file')) {
                        $fail('No puedes enviar tanto la URL de audio como un archivo de audio al mismo tiempo.');
                    }
                },
            ],
        ];
    }
    
    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'language_code.max' => 'El código de idioma no puede superar los 5 caracteres.',
            'audio_url.url' => 'El campo de URL de audio debe ser una URL válida.',
            'audio_file.mimetypes' => 'El archivo debe ser un formato de audio válido (mp3, wav, ogg).',
            'audio_file.max' => 'El archivo de audio no debe superar los 5MB.',
        ];
    }
    
    /**
     * Prepara los datos para la validación.
     * Útil si necesitas normalizar o limpiar la entrada antes de aplicar las reglas.
     */
    protected function prepareForValidation(): void
    {
        // Aseguramos que si se envía una URL vacía, se convierta a null.
        if ($this->has('audio_url') && empty($this->audio_url)) {
            $this->merge(['audio_url' => null]);
        }
    }
}
