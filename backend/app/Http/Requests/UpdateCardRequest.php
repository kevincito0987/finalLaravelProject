<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="UpdateCardRequest",
 * title="Update Card Translation Request",
 * description="Datos opcionales para actualizar una traducción de tarjeta. Permite actualizar la frase clave, subir un nuevo archivo de audio, o enviar una URL de audio existente.",
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
 * property="audio_file",
 * type="string",
 * format="binary",
 * description="Nuevo archivo de audio opcional para reemplazar el existente. Si se envía como string, se usa como URL directa.",
 * nullable=true
 * )
 * )
 */
class UpdateCardRequest extends FormRequest
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
        return [
            'language_code' => ['sometimes', 'string', 'max:5'], 
            'key_phrase' => ['sometimes', 'string'], 
            
            // Regla para el campo de audio: puede ser un archivo O una cadena de texto (URL).
            // Usamos una regla condicional para manejar ambos casos de manera limpia:
            // 1. Si se detecta un archivo (multipart/form-data), valida como file.
            // 2. Si no hay archivo, valida como string (útil para JSON o URL directa).
            'audio_file' => [
                'sometimes',
                'nullable', 
                // La validación real se hará en el controlador para diferenciar File de String.
                // Aquí solo aseguramos que si es un file, cumpla con los requisitos.
                Rule::when($this->hasFile('audio_file'), [
                    'file', 
                    'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp3',
                    'max:5120', // 5MB máximo
                ], [
                    // Si no es un archivo subido, solo puede ser una URL de texto.
                    'string',
                ]),
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
            'audio_file.mimetypes' => 'El archivo debe ser un formato de audio válido (mp3, wav, ogg).',
            'audio_file.max' => 'El archivo de audio no debe superar los 5MB.',
            'audio_file.string' => 'El campo audio_file, si no es un archivo, debe ser una cadena de texto (URL).',
        ];
    }
}
