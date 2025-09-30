<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CardTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define un conjunto de idiomas de prueba para las traducciones
        $languages = ['en', 'es', 'fr', 'de', 'pt'];

        // 1. Obtenemos todos los IDs de las tarjetas sembradas
        $cardIds = Card::pluck('card_id');

        if ($cardIds->isEmpty()) {
            Log::error('CardTranslationSeeder abortado: CardSeeder no se ejecutó correctamente o no creó tarjetas.');
            return;
        }

        $count = 0;
        
        // 2. Iteramos sobre cada ID de tarjeta para asegurar al menos una traducción única por tarjeta.
        foreach ($cardIds as $cardId) {
            // Aseguramos que la primera traducción sea en 'es' (o un idioma principal)
            CardTranslation::factory()->create([
                'card_id_translation' => $cardId,
                'language_code' => 'es', // Primera traducción forzada a español (o tu idioma principal)
            ]);
            $count++;

            // 3. Opcional: Creamos algunas traducciones adicionales en otros idiomas aleatorios
            // Esto solo se hará si hay suficientes idiomas definidos en $languages (más de uno)
            $additionalLanguages = array_diff($languages, ['es']);
            if (!empty($additionalLanguages)) {
                // Tomamos 1 o 2 idiomas adicionales al azar sin repetir
                $randomAdditionalLanguages = collect($additionalLanguages)->random(rand(0, 2));

                foreach ($randomAdditionalLanguages as $langCode) {
                    CardTranslation::factory()->create([
                        'card_id_translation' => $cardId,
                        'language_code' => $langCode,
                    ]);
                    $count++;
                }
            }
        }
            
        Log::info("Se han creado {$count} traducciones de tarjetas de ejemplo de forma única.");
    }
}
