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
        Schema::create('card_translations', function (Blueprint $table) {
            // PK
            $table->id('card_translation_id');
            
            // FK: card_id_translation (Relación con la tabla 'cards')
            // Se asume que 'cards.card_id' usa UNSIGNED BIG INT (default de $table->id())
            $table->foreignId('card_id_translation')
                  ->constrained('cards', 'card_id')
                  ->onDelete('cascade'); // Si la tarjeta se borra, se borran sus traducciones.

            // Language Code (Ej: 'es', 'en', 'fr')
            $table->string('language_code', 5);
            
            // Campos de la Traducción
            $table->text('key_phrase');
            $table->text('audio_path')->nullable(); // Puede no tener un audio asociado
            
            // Clave Única Compuesta: Una tarjeta solo puede tener una traducción por idioma.
            $table->unique(['card_id_translation', 'language_code'], 'card_lang_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_translations');
    }
};
