<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\UserLesson; 
use Illuminate\Support\Facades\Auth;

/**
 * Define las reglas de validación para la actualización de un registro de progreso existente
 * (UserLesson). El ID del registro viene en la ruta.
 */
class UpdateUserLessonRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     * Es crucial verificar que el usuario autenticado solo pueda modificar SU progreso.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Obtenemos la instancia de UserLesson inyectada por Route Model Binding
        $userLesson = $this->route('user_lesson'); 

        // 1. Verificar si la instancia existe y es del tipo correcto.
        if (!$userLesson instanceof UserLesson) {
            // Esto solo ocurre si la inyección de ruta falla gravemente, 
            // pero es una buena práctica de seguridad.
            return false;
        }

        // 2. Verificar si el ID del usuario autenticado coincide con el propietario del registro.
        return $userLesson->user_id === Auth::id();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * Usamos 'sometimes' para permitir actualizaciones parciales.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Nota: La lógica de negocio para evitar desmarcar una lección completada 
        // se maneja mejor en el controlador, no en el Request, para tener mejor control
        // sobre el mensaje de error (400 vs 422).

        return [
            // is_completed es el campo principal a actualizar (marcar como true).
            'is_completed' => [
                'sometimes', 
                'boolean', 
            ],

            // Si se permite actualizar la lección (raro, pero posible):
            'lesson_id' => [
                'sometimes', 
                'integer', 
                'exists:lessons,lesson_id' // <--- CORRECCIÓN CLAVE
            ],
            
            // Si el frontend envía el campo explícitamente (generalmente lo gestiona el backend)
            'completed_at' => [
                'sometimes', 
                'nullable', 
                'date', 
            ],
        ];
    }
}
