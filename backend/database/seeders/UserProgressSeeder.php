<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProgress;
use App\Models\User;
use App\Models\LessonCard;
use Illuminate\Support\Facades\Log;

class UserProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos los registros existentes
        UserProgress::query()->delete();

        // Obtenemos todos los IDs de usuarios y tarjetas existentes
        $userIds = User::pluck('id');
        $cardIds = LessonCard::pluck('id');

        // Si no hay usuarios o tarjetas, detenemos el seeder (o creamos datos dummy si es necesario)
        if ($userIds->isEmpty() || $cardIds->isEmpty()) {
            Log::info("No hay usuarios o LessonCards para sembrar UserProgress.");
            return;
        }

        $records = [];
        $progressesToCreate = 50; // Cantidad de registros de progreso a crear

        for ($i = 0; $i < $progressesToCreate; $i++) {
            // Elegimos IDs aleatorios
            $userId = $userIds->random();
            $cardId = $cardIds->random();
            
            // Usamos una clave única para evitar duplicados en el mismo batch de creación
            $key = "{$userId}-{$cardId}";
            
            // Aseguramos que la combinación (user_id, lesson_card_id) sea única
            if (!isset($records[$key])) {
                // Creamos los datos utilizando el Factory
                $records[$key] = UserProgress::factory()->make([
                    'user_id' => $userId,
                    'lesson_card_id' => $cardId,
                ])->toArray();
            }
        }
        
        // Insertamos los datos únicos
        UserProgress::insert(array_values($records));

        $count = count($records);
        echo "Se crearon {$count} registros únicos de progreso de usuario (UserProgress).\n";
    }
}
