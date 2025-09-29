<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        
        // 1. DESHABILITAR temporalmente las comprobaciones de Clave Foránea
        // Esto permite hacer truncate a una tabla referenciada sin error.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 2. Limpia la tabla (Ahora permitido)
        DB::table('categories')->truncate();

        // 3. Inserta todos los datos de una vez
        DB::table('categories')->insert($categories);

        // 4. RESTABLECER las comprobaciones de Clave Foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
