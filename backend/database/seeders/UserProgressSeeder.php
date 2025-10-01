<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProgress;
use App\Models\User;
use App\Models\Lesson; // Usar Lesson para obtener el lesson_id
use App\Models\Card; // Usar Card para obtener el card_id (Si LessonCard es solo la tabla pivote)
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Necesitamos Carbon para el formateo

class UserProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos los registros existentes
        UserProgress::query()->delete();

        // Obtenemos todos los IDs de usuarios y lecciones existentes
        $userIds = User::pluck('id');
        // NOTA: La tabla user_progress tiene 'lesson_id' y 'card_id'.
        // Si LessonCard es una tabla pivote M:N, debemos asegurarnos de que 
        // los IDs de 'lesson_id' y 'card_id' que usamos existan en sus tablas respectivas (lessons y cards).
        $lessonIds = Lesson::pluck('lesson_id');
        $cardIds = Card::pluck('card_id');


        // Si no hay datos base, detenemos
        if ($userIds->isEmpty() || $lessonIds->isEmpty() || $cardIds->isEmpty()) {
            Log::info("No hay datos base (Users, Lessons, o Cards) para sembrar UserProgress.");
            return;
        }

        $records = [];
        $uniqueKeys = []; // Para garantizar la unicidad
        $progressesToCreate = 150; // Aumentado para mayor variedad, pero sigue siendo un límite de seguridad

        for ($i = 0; $i < $progressesToCreate; $i++) {
            // Elegimos IDs aleatorios
            $userId = $userIds->random();
            $lessonId = $lessonIds->random();
            $cardId = $cardIds->random();
            
            // La clave única es user_id, lesson_id, y card_id
            $key = "{$userId}-{$lessonId}-{$cardId}";
            
            // Aseguramos que la combinación sea única
            if (!in_array($key, $uniqueKeys)) {
                
                // Creamos los datos directamente en array para inserción masiva
                $lastUsedAt = Carbon::now()->subDays(rand(1, 60)); // Una fecha aleatoria
                
                $record = [
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'card_id' => $cardId,
                    
                    'use_count' => rand(1, 20),
                    'score' => rand(0, 5), // Nivel de dominio
                    
                    // CORRECCIÓN CLAVE: Formatear la fecha a 'Y-m-d H:i:s'
                    'last_used_at' => $lastUsedAt->format('Y-m-d H:i:s'), 
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                
                $records[] = $record;
                $uniqueKeys[] = $key;
            }
        }
        
        // Insertamos los datos únicos
        UserProgress::insert($records);

        $count = count($records);
        echo "Se crearon {$count} registros únicos de progreso de usuario (UserProgress).\n";
    }
}
