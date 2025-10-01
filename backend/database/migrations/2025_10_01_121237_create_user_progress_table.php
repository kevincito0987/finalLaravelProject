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
        Schema::create('user_progress', function (Blueprint $table) {
            // Clave Primaria Compuesta (Primary Key)
            // Usamos primary() después de las columnas para definir la clave compuesta.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lesson_card_id')->constrained('lesson_cards')->onDelete('cascade');
            
            // Campos específicos del progreso
            $table->unsignedSmallInteger('score')
                  ->default(0)
                  ->comment('Puntuación o nivel de familiaridad (ej: 0-5).');
            
            $table->dateTime('last_reviewed_at')
                  ->nullable()
                  ->comment('Fecha de la última vez que el usuario revisó la tarjeta.');

            $table->unsignedInteger('review_count')
                  ->default(0)
                  ->comment('Número de veces que se ha revisado la tarjeta.');

            $table->unsignedSmallInteger('consecutive_correct_answers')
                  ->default(0)
                  ->comment('Respuestas correctas consecutivas para algoritmos tipo spaced repetition.');
            
            // Definición de la clave primaria compuesta
            $table->primary(['user_id', 'lesson_card_id']);
            
            // Timestamps automáticos (created_at y updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progresses');
    }
};
