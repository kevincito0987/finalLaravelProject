<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Card;
use App\Models\LessonCard;
use Illuminate\Support\Facades\DB;

class LessonCardSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     */
    public function run(): void
    {
        // 1. Limpiar la tabla antes de sembrar
        DB::table('lesson_cards')->truncate();
        
        // 2. Obtener IDs de lecciones y tarjetas existentes
        $lessonIds = Lesson::pluck('id')->toArray();
        $cardIds = Card::pluck('id')->toArray();

        if (empty($lessonIds) || empty($cardIds)) {
            $this->command->info('No hay suficientes Lecciones o Tarjetas para sembrar LessonCards. Omitting.');
            return;
        }

        // 3. Crear asociaciones LessonCard de ejemplo
        $associations = [];
        $maxCardsPerLesson = 5;

        foreach ($lessonIds as $lessonId) {
            // Seleccionar un número aleatorio de tarjetas para esta lección
            $numCards = rand(3, $maxCardsPerLesson); 
            // Seleccionar tarjetas aleatorias sin repetición
            $selectedCardIds = (array)array_rand(array_flip($cardIds), $numCards); 
            
            $order = 1;
            foreach ($selectedCardIds as $cardId) {
                $associations[] = [
                    'lesson_id' => $lessonId,
                    'card_id' => $cardId,
                    'order_in_lesson' => $order++, // Incrementa el orden secuencialmente
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todas las asociaciones de golpe
        LessonCard::insert($associations);
        
        $this->command->info('LessonCardSeeder ejecutado: ' . count($associations) . ' asociaciones creadas.');
    }
}
