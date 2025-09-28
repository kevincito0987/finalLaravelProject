<?php

namespace Database\Seeders;

use App\Models\CommunicationMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Métodos de comunicación requeridos por el proyecto
        $methods = [
            'visual',
            'auditivo',
            'táctil',
            // Puedes agregar más métodos si es necesario
        ];

        foreach ($methods as $methodName) {
            // Usamos firstOrCreate para evitar duplicados si se ejecuta varias veces
            CommunicationMethod::firstOrCreate(
                ['name' => strtolower($methodName)], // Buscamos por el nombre en minúsculas
                [] // No hay otros campos que llenar
            );
        }

        $this->command->info('Métodos de comunicación iniciales creados.');
    }
}
