<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Category; // Necesitas el Modelo Category
use App\Models\CommunicationMethod; // Necesitas el Modelo CommunicationMethod
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CardFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a la factory.
     *
     * @var string
     */
    protected $model = Card::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Aseguramos que existan categorías y métodos, o los creamos si es necesario.
        $categoryId = Category::inRandomOrder()->first()->category_id ?? Category::factory()->create()->category_id;
        $methodId = CommunicationMethod::inRandomOrder()->first()->method_id ?? CommunicationMethod::factory()->create()->method_id;
        
        $phrase = $this->faker->words(2, true);

        return [
            'uuid' => Str::uuid(), // Genera un UUID único (simulación de RFID)
            'image_path' => 'cards/' . $this->faker->image('public/storage/cards', 400, 300, null, false) . '.png',
            'phrase' => $phrase,
            'audio_path' => 'audios/' . Str::slug($phrase) . '.mp3',
            'method_id' => $methodId,
            'category_id_card' => $categoryId,
        ];
    }
}