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
            $table->id('progress_id'); 
            
            // Usamos nombres estándar (user_id, lesson_id, card_id). 
            // Esto corrige el error de 'user_id_progress' del GET.
            $table->foreignId('user_id')->constrained('users')->references('id')->onDelete('cascade');
            
            // Si la tabla 'lessons' usa 'id' como PK, esta línea es correcta.
            // Si usa 'lesson_id', deberías usar $table->foreignId('lesson_id')->constrained('lessons', 'lesson_id').
            // Asumiremos 'id' como PK para corregir el error 3734.
            $table->foreignId('lesson_id')->constrained('lessons')->references('lesson_id')->onDelete('cascade');
            
            $table->foreignId('card_id')->constrained('cards')->references('card_id')->onDelete('cascade');

            $table->integer('use_count')->default(0);
            $table->integer('score')->default(0);
            $table->timestamp('last_used_at')->nullable();
            
            $table->unique(['user_id', 'lesson_id', 'card_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
