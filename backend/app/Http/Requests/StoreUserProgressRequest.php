<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para registrar el progreso inicial de una tarjeta.
 * No requiere progress_id, ya que se basa en las claves compuestas (user_id, lesson_id, card_id).
 */
class StoreUserProgressRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Asume autorización para fines de ejemplo.
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * El campo clave es 'score' para el nivel de dominio.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Claves compuestas para identificar el progreso (DEBEN existir)
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'lesson_id' => ['required', 'integer', 'exists:lessons,lesson_id'],
            'card_id' => ['required', 'integer', 'exists:cards,card_id'],

            // Dato de progreso: el nuevo Score (alineado con el servicio y la entidad)
            // Asumiendo que el score es un entero entre 0 y 5
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
            'user_id.exists' => 'El usuario especificado no existe.',
        ];
    }
}
