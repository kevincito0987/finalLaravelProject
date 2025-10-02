<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CardSeeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Primero los roles
        $this->call(RoleSeeder::class);

        // Luego el usuario
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Asignar rol viewer
        $viewerRole = Role::where('name', 'viewer')->first();
        if ($viewerRole) {
            $user->roles()->syncWithoutDetaching([$viewerRole->id]);
        }

        $this->call([
            // Asegúrate de agregar tu nuevo seeder aquí
            CommunicationMethodSeeder::class,
            CategorySeeder::class,
            CardSeeder::class,
            CardTranslationSeeder::class,
            LessonSeeder::class,
            EvaluationSeeder::class,
            EvaluationQuestionSeeder::class,
            UserLessonSeeder::class,
            LessonCardSeeder::class,
            UserProgressSeeder::class,
            UserActiveSeeder::class, // Nuevo seeder para usuarios activos/inactivos
        ]);
    }
}