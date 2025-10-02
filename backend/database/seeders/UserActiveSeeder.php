<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserActiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrador Activo (is_active = true)
        // Este usuario debe poder acceder a la aplicación
        User::create([
            'name' => 'Admin Activo PICAPTL',
            'email' => 'admin.activo@picaptl.com',
            'password' => Hash::make('password'), // Contraseña simple para pruebas
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 2. Usuario Inactivo (is_active = false)
        // Este usuario será bloqueado por el middleware y el AuthController
        User::create([
            'name' => 'Usuario Inactivo',
            'email' => 'user.inactivo@picaptl.com',
            'password' => Hash::make('password'), // Contraseña simple para pruebas
            'is_active' => false,
            'email_verified_at' => now(),
        ]);
        
        // Opcional: Usuario normal activo para pruebas adicionales
        User::create([
            'name' => 'Usuario Normal',
            'email' => 'user.normal@picaptl.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
