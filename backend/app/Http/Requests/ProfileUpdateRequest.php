<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="ProfileUpdateRequest",
 * title="Profile Update Request",
 * description="Datos necesarios para actualizar el nombre y/o correo electrónico del perfil de usuario autenticado.",
 * required={"name", "email"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre completo o de usuario. Debe ser una cadena de hasta 255 caracteres.",
 * example="Andrea García"
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Correo electrónico único del usuario. Se almacena en minúsculas.",
 * example="andrea.garcia@example.com"
 * )
 * )
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
