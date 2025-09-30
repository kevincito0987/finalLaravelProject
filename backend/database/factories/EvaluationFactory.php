<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Lesson; // Importa el modelo Lesson
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define el modelo asociado al factory.
     *
     * @var string
     */
    protected $model = Evaluation::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Obtener un ID de lección existente de forma aleatoria.
        // Se recomienda tener el seeder de Lesson ejecutado antes para asegurar que existe data.
        $lesson = Lesson::inRandomOrder()->first();

        // Si no hay lecciones, el factory no puede crear evaluaciones válidas.
        if (!$lesson) {
            throw new \Exception("No se encontraron lecciones en la base de datos para crear la evaluación. Por favor, asegúrate de sembrar el modelo Lesson primero.");
        }

        return [
            // Asigna el ID de la lección encontrada a la clave foránea
            'lesson_id_evaluation' => $lesson->lesson_id,
            
            // Asigna fechas de creación y actualización realistas
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
