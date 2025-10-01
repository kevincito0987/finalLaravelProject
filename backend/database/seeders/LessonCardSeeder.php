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
        // 1. DESHABILITAR TEMPORALMENTE LAS CLAVES FORÁNEAS
        // Esto permite TRUNCATE en tablas que son referenciadas.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. Limpiar la tabla antes de sembrar
        // TRUNCATE es más rápido que DELETE si la tabla está vacía o se vacía completamente.
        DB::table('lesson_cards')->truncate();
        
        // 3. Obtener IDs de lecciones y tarjetas existentes
        // CORRECCIÓN: Usamos 'lesson_id' si esa es la PK de Lesson, o 'id' si Lesson usa la PK por defecto.
        // Asumiendo que Lesson usa 'lesson_id' (por nuestra conversación anterior):
        $lessonIds = Lesson::pluck('lesson_id')->toArray(); 
        
        // Asumiendo que Card usa 'id' por defecto:
        $cardIds = Card::pluck('card_id')->toArray();

        if (empty($lessonIds) || empty($cardIds)) {
            $this->command->info('No hay suficientes Lecciones o Tarjetas para sembrar LessonCards. Omitting.');
            
            // Re-habilitamos antes de retornar si no hay datos.
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // 4. Crear asociaciones LessonCard de ejemplo
        $associations = [];
        $maxCardsPerLesson = 5;

        foreach ($lessonIds as $lessonId) {
            // Seleccionar un número aleatorio de tarjetas para esta lección
            $numCards = rand(3, min($maxCardsPerLesson, count($cardIds))); 
            
            // Seleccionar tarjetas aleatorias sin repetición
            // array_rand con array_flip es la forma más rápida de obtener N IDs únicos de un array de IDs.
            $selectedCardIds = (array)array_rand(array_flip($cardIds), $numCards); 
            
            $order = 1;
            foreach ($selectedCardIds as $cardId) {
                $associations[] = [
                    'lesson_id' => $lessonId, // Aquí se usa el ID correcto de la lección
                    'card_id' => $cardId,
                    'order_in_lesson' => $order++, // Incrementa el orden secuencialmente
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todas las asociaciones de golpe
        LessonCard::insert($associations);
        
        // 5. RE-HABILITAR LAS CLAVES FORÁNEAS
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('LessonCardSeeder ejecutado: ' . count($associations) . ' asociaciones creadas.');
    }
}
