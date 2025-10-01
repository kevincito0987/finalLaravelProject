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
        Schema::create('user_lessons', function (Blueprint $table) {
            $table->id();
            
            // 1. Relación con el usuario (users)
            // La tabla 'users' usa 'id', por lo que esta sintaxis funciona correctamente.
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID del usuario que completa la lección.');

            // 2. Relación con la lección (lessons)
            // Usamos unsignedBigInteger porque la clave primaria en 'lessons' es 'lesson_id' (BIGINT UNSIGNED)
            $table->unsignedBigInteger('lesson_id')
                ->comment('ID de la lección.');
            
            // Definimos la clave foránea explícitamente para apuntar a 'lesson_id' en la tabla 'lessons'.
            $table->foreign('lesson_id')
                  ->references('lesson_id') // <--- CLAVE DE LA CORRECCIÓN
                  ->on('lessons')
                  ->onDelete('cascade');
            
            // Campo de Progreso
            $table->timestamp('completed_at')->nullable()->comment('Fecha y hora en que la lección fue completada.');

            $table->timestamps();

            // Restricción única para evitar duplicados.
            $table->unique(['user_id', 'lesson_id'], 'user_lesson_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lessons');
    }
};
