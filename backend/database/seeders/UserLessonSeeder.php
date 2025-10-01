<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lesson;
use App\Models\UserLesson;
use Illuminate\Database\Seeder;

class UserLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios y lecciones
        $users = User::all();
        $lessons = Lesson::all();

        if ($users->isEmpty() || $lessons->isEmpty()) {
            echo "Aviso: No hay usuarios o lecciones para crear registros de progreso (UserLesson).\n";
            return;
        }

        // Crear progreso para una muestra de usuarios y lecciones
        $users->take(5)->each(function (User $user) use ($lessons) {
            // Un usuario progresará en aproximadamente el 50% de las lecciones
            $lessons->random(rand(1, min(10, $lessons->count())))->each(function (Lesson $lesson) use ($user) {
                
                // Generar un registro de progreso con una probabilidad de completado
                UserLesson::factory()->create([
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                    // El Factory maneja la lógica de 'completed_at'
                ]);
            });
            
            // Opcional: Asegurar que un usuario tiene al menos un registro completado
            if (!UserLesson::where('user_id', $user->id)->whereNotNull('completed_at')->exists()) {
                 UserLesson::factory()->completed()->create([
                    'user_id' => $user->id,
                    'lesson_id' => $lessons->random()->id,
                ]);
            }
        });
        
        echo "Se crearon registros de progreso (UserLesson) para usuarios de prueba.\n";
    }
}
