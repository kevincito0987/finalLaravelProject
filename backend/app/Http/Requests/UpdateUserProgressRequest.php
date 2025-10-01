<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para actualizar el progreso existente de una tarjeta (PUT/PATCH).
 * Al igual que el POST, se basa en las claves compuestas y NO requiere progress_id.
 */
class UpdateUserProgressRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // En una aplicación real, aquí verificarías permisos.
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Claves compuestas para identificar el progreso
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'card_id' => ['required', 'integer', 'exists:cards,id'],

            // Dato de progreso: el nuevo Score
            'score' => ['required', 'integer', 'min:0', 'max:5'],
        ];
    }
    
    /**
     * Personaliza los mensajes de error.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'score.required' => 'El campo de score es obligatorio.',
            'score.min' => 'El score debe ser al menos :min.',
            'score.max' => 'El score no puede ser mayor a :max.',
            'card_id.required' => 'El ID de la tarjeta es obligatorio.',
        ];
    }
}
