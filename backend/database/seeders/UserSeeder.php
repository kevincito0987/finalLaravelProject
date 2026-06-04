<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscamos los IDs reales recién creados por el RoleSeeder
        $adminRole = Role::where('name', 'admin')->first();
        $therapistRole = Role::where('name', 'therapist')->first();
        $patientRole = Role::where('name', 'patient')->first(); // Mapeado como tu rol general/user anterior

        // 1. Usuario Administrador de la plataforma
        User::updateOrCreate(
            ['email' => 'admin@pica.com'],
            [
                'name' => 'Kevin Admin',
                'password' => Hash::make('AdminSecure2026*'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // 2. Usuario Especialista / Terapeuta (Quien asigna las cards)
        User::updateOrCreate(
            ['email' => 'terapeuta@pica.com'],
            [
                'name' => 'Dra. Marta Gómez (Especialista)',
                'password' => Hash::make('TherapistSecure2026*'),
                'role_id' => $therapistRole->id,
                'email_verified_at' => now(),
            ]
        );

        // 3. Usuario Paciente / Usuario final (Fiel a tu diagrama)
        User::updateOrCreate(
            ['email' => 'paciente@pica.com'],
            [
                'name' => 'Carlos Mendoza (Paciente)',
                'password' => Hash::make('PatientSecure2026*'),
                'role_id' => $patientRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
