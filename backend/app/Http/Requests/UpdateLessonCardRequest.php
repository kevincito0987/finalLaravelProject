<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * Valida la actualización de una asociación LessonCard existente (PUT/PATCH).
 * Solo se requiere el campo 'order_in_lesson'.
 * * @OA\Schema(
 * schema="UpdateLessonCardRequest",
 * title="Update Lesson Card Request",
 * description="Datos necesarios para actualizar el orden de una tarjeta dentro de una lección.",
 * required={"order_in_lesson"},
 * @OA\Property(
 * property="order_in_lesson",
 * type="integer",
 * format="int32",
 * description="Nueva posición u orden que tendrá la tarjeta dentro de la lección.",
 * minimum=0,
 * example=5
 * )
 * )
 */
class UpdateLessonCardRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Asume que la autorización se maneja fuera de la validación.
    }

    /**
     * Obtiene las reglas de validación para la actualización (PUT/PATCH).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Solo se permite actualizar el orden de la tarjeta
            'order_in_lesson' => [
                'required',             // Requerido para la actualización de posición
                'integer',              // Debe ser un número entero
                'min:0'                 // Debe ser 0 o un valor positivo
            ],
            // Opcional: si quieres asegurar que no envían accidentalmente otras claves:
            // 'lesson_id_sesion' => ['sometimes', 'prohibited'],
            // 'card_id_sesion' => ['sometimes', 'prohibited'],
        ];
    }
    
    /**
     * Personaliza los mensajes de error.
     */
    public function messages(): array
    {
        return [
            'order_in_lesson.required' => 'El nuevo orden dentro de la lección es obligatorio.',
            'order_in_lesson.integer' => 'El orden debe ser un número entero.',
            'order_in_lesson.min' => 'El orden debe ser un valor positivo o cero.'
        ];
    }
}
