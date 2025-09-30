<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\CardTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTranslationFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a la factory.
     */
    protected $model = CardTranslation::class;

    /**
     * Define el estado predeterminado del modelo.
     */
    public function definition(): array
    {
        // 1. Aseguramos que exista una tarjeta padre.
        // Si no existen tarjetas, creamos una.
        $cardId = Card::inRandomOrder()->first()->card_id ?? Card::factory()->create()->card_id;
        
        // Definimos códigos de idioma comunes.
        $languageCode = $this->faker->randomElement(['es', 'en', 'fr', 'de']);

        $keyPhrase = match ($languageCode) {
            'es' => $this->faker->sentence(3, true),
            'en' => $this->faker->sentence(3, true),
            'fr' => $this->faker->sentence(3, true),
            'de' => $this->faker->sentence(3, true),
            default => $this->faker->sentence(3, true),
        };
        
        $slug = \Illuminate\Support\Str::slug($keyPhrase);

        return [
            'card_id_translation' => $cardId,
            'language_code' => $languageCode,
            'key_phrase' => $keyPhrase,
            'audio_path' => "audios/translations/{$languageCode}/{$slug}.mp3",
        ];
    }
}
