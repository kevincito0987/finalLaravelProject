<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['method_name' => 'visual'],
            ['method_name' => 'auditivo'],
            ['method_name' => 'táctil'],
        ];

        // Insertar solo si la tabla está vacía para evitar duplicados
        // dado que 'method_name' es UNIQUE.
        if (DB::table('communication_methods')->count() === 0) {
            DB::table('communication_methods')->insert($methods);
        }
    }
}
