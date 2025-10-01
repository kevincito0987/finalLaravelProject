<?php

namespace Database\Factories;

use App\Models\UserLesson;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLesson>
 */
class UserLessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserLesson::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define la probabilidad de que una lección esté completada (ej: 60%)
        $isCompleted = $this->faker->boolean(60);

        // CORRECCIÓN CLAVE para Lesson ID: 
        // 1. Intentamos obtener un lesson_id existente (asumiendo que la clave es 'lesson_id').
        // 2. Si la tabla Lessons está vacía (first() retorna null), usamos el operador ?? 
        //    para crear una Lesson de fábrica y obtener su 'lesson_id'.
        $lessonId = Lesson::inRandomOrder()->first()->lesson_id 
                    ?? Lesson::factory()->create()->lesson_id;

        // Asumiendo que User sigue usando 'id' como clave primaria.
        $userId = User::inRandomOrder()->first()->id 
                  ?? User::factory()->create()->id;


        return [
            'user_id' => $userId, 
            'lesson_id' => $lessonId, // Usamos la clave primaria correcta
            
            'completed_at' => $isCompleted 
                ? $this->faker->dateTimeBetween('-1 year', 'now') 
                : null,
            
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
    
    /**
     * Indica que el UserLesson está en progreso (no completado).
     *
     * @return static
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => null,
        ]);
    }

    /**
     * Indica que el UserLesson está completado.
     *
     * @return static
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
