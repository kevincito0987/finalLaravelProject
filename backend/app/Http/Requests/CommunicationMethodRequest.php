<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommunicationMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * En este caso, solo los administradores pueden crear/actualizar métodos.
     * (Aunque las rutas ya lo fuerzan, es una buena práctica de seguridad).
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Se asume que el trait 'hasRole' está implementado en el modelo User
        $user = $this->user();
        return $user && $user->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // Obtiene el ID si estamos en una petición PUT/PATCH (Actualización)
        // El ID viene del segmento de la URL, por ejemplo: /api/communication-methods/5
        $methodId = $this->route('id');

        return [
            'method_name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                // Validación de Unicidad:
                // Ignora el ID del método que se está actualizando (si existe)
                Rule::unique('communication_methods', 'name')->ignore($methodId),
            ],
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
            'method_name.required' => 'El nombre del método de comunicación es obligatorio.',
            'method_name.string' => 'El nombre debe ser una cadena de texto.',
            'method_name.min' => 'El nombre debe tener al menos :min caracteres.',
            'method_name.max' => 'El nombre no puede exceder los :max caracteres.',
            'method_name.unique' => 'Ya existe un método de comunicación con ese nombre.',
        ];
    }
    
    /**
     * Prepara los datos para la validación.
     * Laravel no diferencia entre mayúsculas y minúsculas en UNIQUE por defecto.
     * Para asegurar que 'visual' no colisione con 'VISUAL', se recomienda guardarlos en minúsculas.
     *
     * @return void
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
