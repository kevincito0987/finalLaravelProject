<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Añade el campo 'is_active' a la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            // Se define como booleano y por defecto TRUE para que los usuarios existentes 
            // puedan seguir iniciando sesión.
            $table->boolean('is_active')
                ->default(true)
                ->after('password') 
                ->comment('Indica si el usuario está activo y puede iniciar sesión.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Elimina el campo 'is_active' de la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
