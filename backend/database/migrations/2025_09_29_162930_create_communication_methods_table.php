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
        // Crea la tabla communication_methods
        Schema::create('communication_methods', function (Blueprint $table) {
            // El campo PK 'method_id' como entero autoincrementable sin signo
            $table->unsignedInteger('method_id')->autoIncrement();

            // El campo 'method_name' con longitud 100, debe ser único para evitar duplicados
            $table->string('method_name', 100)->unique();
            
            // Asumiendo que esta tabla no necesita timestamps (created_at, updated_at)
            // Si los necesitas, descomenta: $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_methods');
    }
};
