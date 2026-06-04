<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password123'), // Contraseña por defecto segura para testing
            'profile_photo_path' => fake()->imageUrl(640, 480, 'people'),
            'remember_token' => Str::random(10),

            // Por defecto, si creas un usuario con el factory sin especificar rol, le asignará el de menor rango
            'role_id' => function () {
                return Role::where('name', 'patient')->first()?->id ?? Role::factory();
            },
            'is_mfa_enabled' => false,
            'mfa_secret' => null,
        ];
    }

    /**
     * Estados dinámicos para pruebas rápidas (Convenience States)
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('name', 'admin')->first()?->id,
        ]);
    }

    public function therapist(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('name', 'therapist')->first()?->id,
        ]);
    }
}
