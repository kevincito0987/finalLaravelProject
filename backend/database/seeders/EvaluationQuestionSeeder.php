<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// CAMBIAR: Asegúrate de que esta línea es correcta
use App\Models\EvaluationQuestion; // <--- DEBE APUNTAR AQUÍ

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // IMPORTANTE: Asegúrate de que las tablas 'evaluations' y 'lesson_cards'
        // tengan datos antes de ejecutar este Seeder.
        // ---------------------------------------------------------------------

        // Aquí, EvaluationQuestion::factory() debe llamar al factory asociado
        // con el MODELO de Eloquent.
        EvaluationQuestion::factory()
            ->count(25);

        $this->command->info('Se han generado 25 preguntas de evaluación (EvaluationQuestion).');
    }
}
