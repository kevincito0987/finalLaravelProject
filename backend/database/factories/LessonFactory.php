<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tipos de lección válidos para el campo ENUM
        $lessonTypes = ['video', 'reading', 'quiz', 'activity'];

        return [
            'lessonName' => $this->faker->unique()->sentence(4),
            'description' => $this->faker->paragraph(3),
            'lessonType' => $this->faker->randomElement($lessonTypes),
        ];
    }
}
