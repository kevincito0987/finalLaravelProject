<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla 'evaluations' para registrar el inicio de una sesión de evaluación.
     * Solo contiene el enlace a la lección. El score y los resultados van en otra tabla.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            // Clave primaria de la evaluación
            $table->id('evaluation_id');

            // Clave foránea para enlazar a la lección que se está evaluando
            $table->foreignId('lesson_id_evaluation')
                  ->constrained('lessons', 'lesson_id') // Asume que la tabla Lessons tiene la PK 'lesson_id'
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Si la lección se borra, también se borra la evaluación.

            // Columnas de timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
    