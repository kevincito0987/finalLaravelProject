<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserProgressRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Generalmente, se permite a los usuarios autenticados crear registros de progreso.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Nota: Asumo que 'cards' es el nombre de la tabla de LessonCard
        // y que 'card_id_evaluation' es la clave primaria en esa tabla.
        return [
            // Clave foránea del Usuario (requerida)
            'user_id_progress' => [
                'required', 
                'integer', 
                'exists:users,id' // Valida que exista en la tabla 'users'
            ],
            
            // Clave foránea de la Card (requerida)
            'card_id_progress' => [
                'required', 
                'integer', 
                'exists:cards,card_id_evaluation' // Valida que exista en la tabla 'cards' usando su PK
            ],
            
            // Contador de uso inicial (requerido, debe ser un entero >= 0)
            'use_count' => [
                'required', 
                'integer', 
                'min:0'
            ],
            
            // Fecha/hora de último uso. Opcional en la creación, pero si se envía, debe ser un formato válido.
            'last_used_at' => [
                'nullable',
                'date' // Asegura que es un formato de fecha/hora válido
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
            'user_id_progress.required' => 'El ID del usuario es obligatorio.',
            'user_id_progress.exists' => 'El usuario especificado no existe.',
            'card_id_progress.required' => 'El ID de la ficha es obligatorio.',
            'card_id_progress.exists' => 'La ficha especificada no existe.',
            'use_count.required' => 'El contador de uso es obligatorio.',
            'use_count.min' => 'El contador de uso debe ser como mínimo :min.',
            'last_used_at.date' => 'El formato de fecha de último uso no es válido.',
        ];
    }
}
