<?php

namespace Database\Factories;

use App\Models\UserProgress;
use App\Models\User; // Asumimos que necesitas Users, Lessons y Cards
use App\Models\Lesson;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProgress>
 */
class UserProgressFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     * @var string
     */
    protected $model = UserProgress::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Nota: Asegúrate de que las tablas User, Lesson y Card ya tienen datos o factorías funcionando.
        
        // Seleccionamos un ID de usuario, lección y tarjeta aleatorios.
        $userId = User::factory();
        $lessonId = Lesson::factory();
        $cardId = Card::factory();

        return [
            // Los IDs se resuelven automáticamente con el estado
            'user_id' => $userId, 
            'lesson_id' => $lessonId,
            'card_id' => $cardId,
            
            // Datos de progreso
            'use_count' => $this->faker->numberBetween(1, 50),
            'score' => $this->faker->numberBetween(0, 5), // Nivel de dominio
            'last_used_at' => Carbon::now()->subDays($this->faker->numberBetween(1, 30)),
        ];
    }
}
