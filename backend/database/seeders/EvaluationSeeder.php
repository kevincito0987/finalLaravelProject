<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluation;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class EvaluationSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de la base de datos.
     *
     * @return void
     */
    public function run(): void
    {
        // Verifica que existan lecciones para asociar las evaluaciones
        if (Lesson::count() === 0) {
            $this->command->info('No hay lecciones. Omite la siembra de evaluaciones.');
            return;
        }

        // Limpia la tabla antes de sembrar (opcional, pero útil para evitar duplicados)
        DB::table('evaluations')->delete();

        // Genera 20 evaluaciones aleatorias
        Evaluation::factory()
            ->count(20)
            ->create();

        $this->command->info('Se han sembrado 20 evaluaciones exitosamente.');
    }
}
