<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define el estado por defecto del modelo.
     */
    public function definition(): array
    {
        return [
            // Genera palabras únicas aleatorias de forma segura para testing (ej: roles dinámicos)
            'name' => fake()->unique()->word(),
        ];
    }
}