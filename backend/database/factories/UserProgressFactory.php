<?php

namespace Database\Factories;

use App\Models\UserProgress;
use App\Models\User;
use App\Models\LessonCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Aseguramos que existan usuarios y lesson_cards para usar sus IDs
        $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
        $lessonCardId = LessonCard::inRandomOrder()->first()->id ?? LessonCard::factory()->create()->id;

        $reviewCount = $this->faker->numberBetween(1, 20);
        
        return [
            // Usamos un ID de usuario que ya exista
            'user_id' => $userId, 
            // Usamos un ID de LessonCard que ya exista
            'lesson_card_id' => $lessonCardId, 
            'score' => $this->faker->numberBetween(1, 5), // Puntuación de 1 a 5
            'last_reviewed_at' => Carbon::now()->subDays($this->faker->numberBetween(1, 30)),
            'review_count' => $reviewCount,
            'consecutive_correct_answers' => $this->faker->numberBetween(0, 5),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
