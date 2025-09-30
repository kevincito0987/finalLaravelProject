<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id('lesson_id'); 
            $table->string('lessonName', 100);
            $table->text('description');
            // Aumentamos la longitud de la cadena a 50
            $table->string('lessonType', 50); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
