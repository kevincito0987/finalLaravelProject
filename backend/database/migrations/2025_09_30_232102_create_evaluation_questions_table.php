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
        Schema::create('evaluation_questions', function (Blueprint $table) {
            // Clave primaria (PK)
            $table->id('question_id');

            // 1. Llave Foránea (FK) a la tabla 'evaluations'
            // Asumo que la PK de 'evaluations' es 'evaluation_id'
            $table->unsignedBigInteger('evaluation_id_question');
            $table->foreign('evaluation_id_question')
                  ->references('evaluation_id')
                  ->on('evaluations')
                  ->onDelete('cascade');

            // 2. Llave Foránea (FK) a la tabla 'cards' (Corregido)
            // Esto asume que la tabla que almacena los contenidos se llama 'cards' 
            // y que su PK es 'card_id'.
            $table->unsignedBigInteger('card_id_evaluation');
            $table->foreign('card_id_evaluation')
                  ->references('card_id') // Apunta a la PK en la tabla referenciada
                  ->on('cards')           // <--- CAMBIO CLAVE: Referencia la tabla 'cards'
                  ->onDelete('cascade');

            // Campos de la pregunta
            $table->text('question_text');
            $table->string('correct_answer', 255);
            $table->text('options'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
