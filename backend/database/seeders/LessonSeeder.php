<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creamos 50 lecciones de prueba usando el factory
        Lesson::factory()->count(50)->create();
        
        $this->command->info('50 Lecciones de prueba creadas exitosamente.');
    }
}
