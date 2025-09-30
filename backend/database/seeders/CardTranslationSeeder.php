<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CardTranslationSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
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
            
            // Verificamos si ya existe una traducción en español (para evitar duplicados si se corre varias veces)
            $existsEs = CardTranslation::where('card_id_translation', $cardId)
                                     ->where('language_code', 'es')
                                     ->exists();
            
            if (!$existsEs) {
                // Aseguramos que la primera traducción sea en 'es' (o un idioma principal)
                CardTranslation::factory()->language('es', 'Traducción base en Español')->create([
                    'card_id_translation' => $cardId,
                ]);
                $count++;
            }


            // 3. Opcional: Creamos algunas traducciones adicionales en otros idiomas aleatorios
            $additionalLanguages = array_diff($languages, ['es']);
            if (!empty($additionalLanguages)) {
                // Tomamos 1 o 2 idiomas adicionales al azar sin repetir
                $randomAdditionalLanguages = collect($additionalLanguages)->random(rand(0, 2));

                foreach ($randomAdditionalLanguages as $langCode) {
                    
                    // Solo creamos si no existe ya una traducción para ese idioma y tarjeta
                    $existsOther = CardTranslation::where('card_id_translation', $cardId)
                                                ->where('language_code', $langCode)
                                                ->exists();
                                                
                    if (!$existsOther) {
                        // Usamos la capacidad de 'state' del Factory para definir el idioma
                        CardTranslation::factory()->language($langCode, 'Sample Phrase in ' . strtoupper($langCode))->create([
                            'card_id_translation' => $cardId,
                        ]);
                        $count++;
                    }
                }
            }
        }
            
        Log::info("Se han creado {$count} nuevas traducciones de tarjetas de ejemplo de forma única.");
    }
}
