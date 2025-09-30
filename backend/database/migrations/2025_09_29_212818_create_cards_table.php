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
            
            // ELIMINADOS: 'phrase' y 'audio_path'
            // $table->string('phrase', 255); 
            // $table->string('audio_path', 255)->nullable(); 
            
            // FK: method_id (MÉTODO DE COMUNICACIÓN)
            // Usamos unsignedInteger para coincidir con la PK de communication_methods (si no es BigInt)
            $table->unsignedInteger('method_id');
            $table->foreign('method_id')->references('method_id')->on('communication_methods')->onDelete('cascade');
            
            // FK: category_id_card (CATEGORÍA)
            // Se asume que 'categories.category_id' usa UNSIGNED BIG INT (default de $table->id())
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