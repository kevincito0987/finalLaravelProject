<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'profile_image_path' => $this->faker->imageUrl(640, 480, 'people', true, 'profile'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withrole(string $roleName = 'viewer'): static
    {
        return $this->afterCreating(function (User $user) use ($roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $user->role_id = $role->id;
            $user->save();
        });
    }

    public function withroles(array $roleNames = ['viewer']): static
    {
        return $this->afterCreating(function (User $user) use ($roleNames) {
            foreach ($roleNames as $name) {
                $role = Role::firstOrCreate(['name' => $name]);
                $user->roles()->attach($role->id);
            }
        });
    }
}
