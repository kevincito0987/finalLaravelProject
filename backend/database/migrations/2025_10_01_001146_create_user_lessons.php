<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_lessons', function (Blueprint $table) {
            $table->id();
            
            // Clave foránea para User (asumiendo que User tiene 'id')
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // CORRECCIÓN CLAVE: 
            // 1. Define la columna con el tipo correcto (unsignedBigInteger es lo más común para IDs).
            $table->unsignedBigInteger('lesson_id');
            // 2. Define la restricción foránea, indicando la columna 'lesson_id' en la tabla 'lessons'.
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->cascadeOnDelete();
            
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Aseguramos que la combinación user_id y lesson_id sea única
            $table->unique(['user_id', 'lesson_id']); 
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('user_lessons');
    }
};
