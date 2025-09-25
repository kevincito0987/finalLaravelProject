<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'user'], ['label' => 'Lector']);
        Role::firstOrCreate(['name' => 'therapist'], ['label' => 'Editor/Editora']);
        Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrador VIP']);
    }
}