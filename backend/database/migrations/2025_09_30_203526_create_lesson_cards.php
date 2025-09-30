<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        // Esta tabla pivote asocia Lesson y Card, y define el orden.
        Schema::create('lesson_cards', function (Blueprint $table) {
            
            // ID primario autoincremental simple para la tabla pivote.
            $table->id();

            // 1. Clave Foránea a la tabla 'lessons'.
            // Referencia la PK personalizada 'lesson_id' en la tabla 'lessons'.
            $table->unsignedBigInteger('lesson_id');
            $table->foreign('lesson_id')
                ->references('lesson_id') // <-- Referencia a tu PK 'lesson_id'
                ->on('lessons')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // 2. Clave Foránea a la tabla 'cards'.
            // Referencia la PK personalizada 'card_id' en la tabla 'cards'.
            // Nota: Usamos unsignedBigInteger porque $table->id() crea un BIGINT.
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')
                ->references('card_id') // <-- Referencia a tu PK 'card_id'
                ->on('cards')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Campo para el orden.
            $table->unsignedSmallInteger('order_in_lesson');

            // Restricción única compuesta:
            $table->unique(['lesson_id', 'card_id']);
            
            // Restricción única de orden:
            $table->unique(['lesson_id', 'order_in_lesson']);

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_cards');
    }
};
