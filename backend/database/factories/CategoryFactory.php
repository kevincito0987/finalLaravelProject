<?php

namespace Database\Factories;

use App\Models\Category; // Usamos el modelo de Eloquent
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de nombres de categorías que son relevantes para el proyecto
        // (Comunicación, Alimentos, Sentimientos, Acciones, etc.)
        $categoryNames = [
            'Comida', 
            'Sentimientos', 
            'Acciones', 
            'Lugares', 
            'Gente', 
            'Objetos', 
            'Animales', 
            'Ropa'
        ];
        
        // Retorna un estado con un nombre de categoría aleatorio
        return [
            // Usamos 'category_name' que es el campo de tu tabla
            'category_name' => $this->faker->unique()->randomElement($categoryNames) . ' (' . $this->faker->unique()->randomNumber(2) . ')',
            // El sufijo aleatorio asegura que el nombre sea único en la base de datos 
            // aunque se repita el elemento del array original
        ];
    }
}