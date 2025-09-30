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
        // 1. Aseguramos que existan categorías y métodos.
        // Si no existen, los crea la factory de fallback.
        $categoryId = Category::inRandomOrder()->first()->category_id ?? Category::factory()->create()->category_id;
        $methodId = CommunicationMethod::inRandomOrder()->first()->method_id ?? CommunicationMethod::factory()->create()->method_id;
        
        // Eliminados: $phrase y su generación
        
        return [
            // Campos requeridos en la DB:
            'uuid' => Str::uuid(), // Genera un UUID único (esencial)
            'image_path' => 'https://placehold.co/400x300/F0F0F0/2C3E50/png?text=Tarjeta+' . $this->faker->numberBetween(1, 100),
            
            // Eliminados: 'phrase' y 'audio_path'
            
            // Claves Foráneas:
            'method_id' => $methodId,
            'category_id_card' => $categoryId,
        ];
    }
}