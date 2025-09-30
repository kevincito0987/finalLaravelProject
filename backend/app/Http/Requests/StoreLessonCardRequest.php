<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * schema="StoreLessonCardRequest",
 * title="Store Lesson Card Request",
 * description="Datos necesarios para crear una nueva asociación LessonCard (agregar una tarjeta a una lección).",
 * required={"lesson_id_sesion", "card_id_sesion", "order_in_lesson"},
 * @OA\Property(
 * property="lesson_id_sesion",
 * type="integer",
 * description="ID de la lección (Lesson) a la que se vinculará la tarjeta. Debe existir en la tabla 'lessons' bajo la columna 'lesson_id'.",
 * example=101
 * ),
 * @OA\Property(
 * property="card_id_sesion",
 * type="integer",
 * description="ID de la tarjeta (Card) que se vinculará a la lección. Debe existir en la tabla 'cards' bajo la columna 'card_id'.",
 * example=205
 * ),
 * @OA\Property(
 * property="order_in_lesson",
 * type="integer",
 * format="int32",
 * description="Posición u orden que tendrá la tarjeta dentro de la lección.",
 * minimum=0,
 * example=3
 * )
 * )
 */
class StoreLessonCardRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Asumiendo que solo los terapeutas y administradores pueden modificar lecciones.
        // Las políticas de Laravel (Policies) son la mejor práctica, pero usamos roles por simplicidad.
        // Si ya estás usando un middleware 'role:therapist,admin', esto puede ser true.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * Se requiere el uso de 'exists:table,column' para referenciar las PK personalizadas.
     */
    public function rules(): array
    {
        return [
            // CRÍTICO: El nombre del campo en el request es '_sesion', pero debe validar la existencia
            // en la columna 'lesson_id' de la tabla 'lessons'.
            'lesson_id_sesion' => [
                'required', 
                'integer', 
                'exists:lessons,lesson_id' // ¡CORREGIDO! Busca en la columna lesson_id
            ],
            
            // CRÍTICO: El nombre del campo en el request es '_sesion', pero debe validar la existencia
            // en la columna 'card_id' de la tabla 'cards'.
            'card_id_sesion' => [
                'required', 
                'integer', 
                'exists:cards,card_id' // ¡CORREGIDO! Busca en la columna card_id
            ],
            
            // El campo de orden.
            'order_in_lesson' => [
                'required',             
                'integer',              
                'min:0'                 
            ],
        ];
    }

    /**
     * Personaliza los mensajes de error.
     */
    public function messages(): array
    {
        return [
            'lesson_id_sesion.exists' => 'La ID de la lección proporcionada no es válida.',
            'card_id_sesion.exists' => 'La ID de la tarjeta proporcionada no es válida.',
            'order_in_lesson.required' => 'El campo de orden es obligatorio.',
            'order_in_lesson.integer' => 'El campo de orden debe ser un número entero.',
            'order_in_lesson.min' => 'El campo de orden debe ser un número positivo (0 o mayor).',
        ];
    }
}
