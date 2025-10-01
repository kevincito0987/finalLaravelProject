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

        return [
            // Asume que los modelos User y Lesson existen y se obtienen sus IDs
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            
            'completed_at' => $isCompleted 
                ? $this->faker->dateTimeBetween('-1 year', 'now') // Fecha de completado si está completada
                : null, // Null si está en progreso
            
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
