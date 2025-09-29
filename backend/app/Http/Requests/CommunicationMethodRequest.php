<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommunicationMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * En este caso, solo los administradores pueden crear/actualizar métodos.
     */
    public function authorize(): bool
    {
        // Se asume que el trait 'hasRole' está implementado en el modelo User
        $user = $this->user();
        return $user && $user->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // El ID del método se obtiene del parámetro de la URL, que en tu ruta se llama 'methodId'
        $methodId = $this->route('methodId'); // <-- CORRECCIÓN AQUÍ

        return [
            'method_name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                // Validación de Unicidad:
                // Ignora el ID del método que se está actualizando (si existe)
                // Se asume que la columna es 'method_name' (el campo) y 'method_id' (la PK)
                Rule::unique('communication_methods', 'method_name')->ignore($methodId, 'method_id'),
            ],
        ];
    }

    /**
     * Personaliza los mensajes de error.
     */
    public function messages(): array
    {
        return [
            'method_name.required' => 'El nombre del método de comunicación es obligatorio.',
            'method_name.string' => 'El nombre debe ser una cadena de texto.',
            'method_name.min' => 'El nombre debe tener al menos :min caracteres.',
            'method_name.max' => 'El nombre no puede exceder los :max caracteres.',
            'method_name.unique' => 'Ya existe un método de comunicación con ese nombre.',
        ];
    }
    
    /**
     * Prepara los datos para la validación (estandarización a minúsculas).
     */
    protected function prepareForValidation(): void
    {
        // Almacenamos el nombre en minúsculas para estandarización y chequeo de unicidad
        if ($this->has('method_name')) {
            $this->merge([
                'method_name' => strtolower($this->method_name),
            ]);
        }
    }
}
    