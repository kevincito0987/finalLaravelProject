<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProgressRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Permitir a los usuarios autenticados actualizar su progreso.
        // Aquí podrías agregar lógica para asegurar que solo el dueño del progreso pueda actualizarlo.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Usamos 'sometimes' para que el campo sea opcional, 
        // pero si se envía, debe cumplir las reglas.
        return [
            // Clave foránea del Usuario
            'user_id_progress' => [
                'sometimes', 
                'integer', 
                'exists:users,id' // Debe existir en la tabla 'users'
            ],
            
            // Clave foránea de la Card
            'card_id_progress' => [
                'sometimes', 
                'integer', 
                'exists:cards,card_id_evaluation' // Debe existir en la tabla 'cards' con la PK correcta
            ],
            
            // Contador de uso
            'use_count' => [
                'sometimes', 
                'integer', 
                'min:0' // No puede ser negativo
            ],

            // Fecha/hora de último uso
            'last_used_at' => [
                'sometimes',
                'date' // Debe tener un formato de fecha/hora válido
            ],
        ];
    }
    
    /**
     * Personaliza los mensajes de error para la validación.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_id_progress.exists' => 'El ID de usuario proporcionado no es válido.',
            'card_id_progress.exists' => 'El ID de ficha proporcionado no es válido.',
            'use_count.min' => 'El contador de uso debe ser como mínimo :min.',
            'last_used_at.date' => 'El formato de fecha de último uso no es válido.',
        ];
    }
}
