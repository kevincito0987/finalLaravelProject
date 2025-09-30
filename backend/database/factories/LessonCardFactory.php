<?php

namespace Database\Factories;

use App\Models\LessonCard;
use App\Models\Lesson;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonCard>
 */
class LessonCardFactory extends Factory
{
    /**
     * El modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = LessonCard::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // **IMPORTANTE:** Para que este factory funcione correctamente durante el seeding,
        // las tablas 'lessons' y 'cards' deben tener registros existentes.
        
        return [
            // Asigna un ID de lección aleatorio existente
            'lesson_id' => Lesson::factory(), 

            // Asigna un ID de tarjeta aleatorio existente
            'card_id' => Card::factory(), 

            // Define un orden aleatorio.
            // Nota: En un seeder real, deberás calcular el orden secuencialmente.
            'order_in_lesson' => $this->faker->numberBetween(1, 10), 
        ];
    }
    
    /**
     * Configura un LessonCard con IDs específicos y un orden dado.
     * Esto es útil para el seeder, donde quieres controlar los valores.
     *
     * @param int $lessonId
     * @param int $cardId
     * @param int $order
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSpecifics(int $lessonId, int $cardId, int $order): Factory
    {
        return $this->state(fn (array $attributes) => [
            'lesson_id' => $lessonId,
            'card_id' => $cardId,
            'order_in_lesson' => $order,
        ]);
    }
}
