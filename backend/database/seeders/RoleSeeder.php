<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Corre los inserts iniciales de los roles del sistema.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],       // Control total de la plataforma
            ['name' => 'therapist'],   // Especialista/Profesor (Asigna cards y gestiona pacientes)
            ['name' => 'patient'],     // Usuario final con el trastorno de lenguaje
        ];

        foreach ($roles as $role) {
            // updateOrCreate evita duplicados si vuelves a correr el seeder por error
            Role::updateOrCreate(
                ['name' => $role['name']], 
                $role
            );
        }
    }
}