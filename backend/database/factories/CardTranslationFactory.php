<?php

namespace Database\Factories;

use App\Models\CardTranslation;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CardTranslation>
 */
class CardTranslationFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = CardTranslation::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define una lista de frases comunes y su código de idioma asociado
        $translations = [
            ['es', 'Hola'],
            ['es', 'Adiós'],
            ['es', 'Gracias'],
            ['en', 'Hello'],
            ['en', 'Goodbye'],
            ['en', 'Thank you'],
            ['fr', 'Bonjour'],
            ['fr', 'Au revoir'],
            ['fr', 'Merci'],
        ];

        // Selecciona una traducción al azar
        $translation = $this->faker->randomElement($translations);
        
        // Simula la ruta de un archivo de audio (puede ser null)
        $audioPath = $this->faker->boolean(70) // 70% de probabilidad de tener audioPath
            ? 'audio/' . $translation[0] . '/' . $this->faker->uuid() . '.mp3'
            : null;

        return [
            // FK: Aseguramos que la traducción esté vinculada a una Card existente
            // Usamos 'card_id_translation' ya que es el nombre de la columna en la BD.
            'card_id_translation' => Card::factory(), 
            
            // Columna 'language_code' (ej: 'es', 'en', 'fr')
            'language_code' => $translation[0], 
            
            // Columna 'key_phrase'
            'key_phrase' => $translation[1], 
            
            // Columna 'audio_path' (puede ser null)
            'audio_path' => $audioPath, 
        ];
    }

    /**
     * Indica que esta traducción debe ser para un idioma específico.
     */
    public function language(string $code, string $phrase): static
    {
        return $this->state(fn (array $attributes) => [
            'language_code' => $code,
            'key_phrase' => $phrase,
        ]);
    }
}
