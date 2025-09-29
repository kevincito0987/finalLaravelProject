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
        Schema::create('categories', function (Blueprint $table) {
            // Clave Primaria (PK)
            // Usa 'unsignedBigInteger' para ID si planeas tener muchas categorías, 
            // pero 'id()' es una abreviatura común que usa unsignedBigInteger.
            // Dado que tu DER usa 'category_id', usaremos 'id()' y lo renombraremos
            $table->id('category_id'); 

            // Campo category_name: VARCHAR(100) y debe ser único
            // El campo es crucial para la lógica de tu CRUD.
            $table->string('category_name', 100)->unique(); 
            
            // La documentación original indica que los modelos de Categoría no tienen timestamps.
            // Si quieres que no haya columnas 'created_at' y 'updated_at', no incluyas:
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};