<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Añadido para inserción masiva si es necesario

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define las categorías centrales para la comunicación alternativa
        $categories = [
            ['category_name' => 'Necesidades'],
            ['category_name' => 'Sentimientos'],
            ['category_name' => 'Acciones'],
            ['category_name' => 'Comida'],
            ['category_name' => 'Lugares'],
            ['category_name' => 'Personas'],
            ['category_name' => 'Objetos'],
            ['category_name' => 'Juegos'],
            ['category_name' => 'Animales'],
            ['category_name' => 'Adjetivos'],
            ['category_name' => 'Emergencias'],
        ];
        
        // Limpia la tabla y la vuelve a poblar para evitar duplicados en la ejecución de seed
        DB::table('categories')->truncate();

        // Inserta todos los datos de una vez para mayor eficiencia
        DB::table('categories')->insert($categories);
        
        // Opcional: Usando el Modelo para tener acceso a eventos si los hubiera
        /*
        foreach ($categories as $category) {
            Category::create($category);
        }
        */
    }
}