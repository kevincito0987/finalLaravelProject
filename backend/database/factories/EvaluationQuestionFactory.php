<?php

namespace Database\Factories;

use App\Core\Entities\EvaluationQuestion;
use App\Models\Evaluation; // Asume el modelo Eloquent en App\Models
use App\Models\Card;       // Asume el modelo Eloquent en App\Models
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\Entities\EvaluationQuestion>
 */
class EvaluationQuestionFactory extends Factory
{
    /**
     * El nombre del modelo que corresponde a este factory.
     *
     * @var string
     */
    protected $model = EvaluationQuestion::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Define las opciones de respuesta y elige una como correcta
        $possibleAnswers = [
            $this->faker->word(),
            $this->faker->word(),
            $this->faker->word(),
            $this->faker->word(),
        ];
        
        $correctAnswer = $this->faker->randomElement($possibleAnswers);

        // 2. Define el texto de la pregunta
        $questionTexts = [
            '¿Qué acción representa correctamente esta Card?',
            'Selecciona la respuesta correcta para la imagen mostrada.',
            '¿Cuál de estas opciones describe mejor la Card?',
            'Identifica la función principal de esta herramienta.',
            'Asocia el concepto de la Card con su nombre.',
        ];


        return [
            // Claves Foráneas
            'evaluation_id_question' => Evaluation::factory(), 
            'card_id_evaluation' => Card::factory(),
            
            // Campos de Pregunta
            'question_text' => $this->faker->randomElement($questionTexts),
            
            // La respuesta correcta (VARCHAR(50))
            'correct_answer' => $correctAnswer,
            
            // Las opciones de respuesta (simuladas como un JSON string, TEXT)
            // Esto asume que 'options' es un campo de texto que almacena un array serializado.
            'options' => json_encode($possibleAnswers),
            
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
