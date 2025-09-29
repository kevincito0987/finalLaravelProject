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
        Schema::create('cards', function (Blueprint $table) {
            // PK
            $table->id('card_id'); 
            
            // UUID (Simulación de RFID, debe ser único)
            $table->string('uuid', 36)->unique(); 
            
            // Campos de la Tarjeta
            $table->text('image_path');
            $table->string('phrase', 255); 
            $table->string('audio_path', 255)->nullable(); 
            
            // FK: method_id (MÉTODO DE COMUNICACIÓN)
            // CAMBIO: Debe ser unsignedInteger para MATCH con la PK de communication_methods
            $table->unsignedInteger('method_id');
            $table->foreign('method_id')->references('method_id')->on('communication_methods')->onDelete('cascade');
            
            // FK: category_id_card (CATEGORÍA)
            // category_id en 'categories' es unsignedBigInt ($table->id), por lo que foreignId es correcto
            $table->foreignId('category_id_card')
                  ->constrained('categories', 'category_id') 
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};