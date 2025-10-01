<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * Define las reglas de validación para la creación de un nuevo registro de progreso
 * (UserLesson), tipicamente para iniciar o completar una lección.
 */
class StoreUserLessonRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     * Solo el usuario autenticado puede crear un registro de progreso para sí mismo.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Se asume que el usuario está autenticado.
        return Auth::check();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Nota: El 'user_id' se obtendrá automáticamente del usuario autenticado en el controlador.
        $userId = $this->user()->id;

        return [
            // lesson_id es obligatorio y debe existir en la columna 'lesson_id' de la tabla 'lessons'.
            'lesson_id' => [
                'required', 
                'integer', 
                'exists:lessons,lesson_id', // <--- CORRECCIÓN CLAVE
                // Validación para evitar duplicados: No permitir crear un registro
                // si ya existe un progreso para este usuario y esta lección.
                Rule::unique('user_lessons')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            
            // Opcional: Si es true, se marcará como completada de inmediato.
            'is_completed' => ['nullable', 'boolean'],
        ];
    }
    
    /**
     * Prepara los datos para la validación (se ejecuta antes de 'rules').
     */
    protected function prepareForValidation()
    {
        // Opcional: Aseguramos que 'is_completed' sea booleano si se envía
        if ($this->has('is_completed') && is_string($this->is_completed)) {
             $this->merge([
                'is_completed' => filter_var($this->is_completed, FILTER_VALIDATE_BOOLEAN),
             ]);
        }
    }
}
