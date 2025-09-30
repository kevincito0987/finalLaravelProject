<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Category;
use App\Models\CommunicationMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Necesario para generar UUID

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos todos los IDs de las tablas padre sembradas
        $categoryIds = Category::pluck('category_id');
        $methodIds = CommunicationMethod::pluck('method_id');

        if ($categoryIds->isEmpty() || $methodIds->isEmpty()) {
            // Esto es crucial: si no hay categorías o métodos, el seeder debe detenerse
            // para evitar errores de clave foránea.
            Log::error('CardSeeder abortado: CategorySeeder o CommunicationMethodSeeder no se ejecutaron correctamente o no crearon registros.');
            return;
        }

        // Creamos 50 tarjetas de ejemplo
        for ($i = 0; $i < 50; $i++) {
            Card::create([
                // Simulación de UUID único
                'uuid' => (string) Str::uuid(),
                // Usamos un placeholder genérico
                'image_path' => 'placeholders/card_image_' . ($i + 1) . '.jpg', 
                
                // ELIMINADOS: 'phrase' y 'audio_path'
                
                // Asignamos una clave foránea aleatoria de las colecciones que obtuvimos
                'method_id' => $methodIds->random(),
                'category_id_card' => $categoryIds->random(),
            ]);
        }
    }
}