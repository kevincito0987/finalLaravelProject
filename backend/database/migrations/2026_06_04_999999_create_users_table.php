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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // PK id INTEGER
            
            // FK role_id INTEGER -> Enlace directo a tu tabla de roles
            $table->foreignId('role_id')
                  ->constrained('roles')
                  ->onDelete('restrict'); // OWASP: Evita que se borre un rol si tiene usuarios asociados
            
            $table->string('name', 255); // VARCHAR(255)
            $table->string('email', 255)->unique(); // VARCHAR(255) U
            $table->string('password', 255); // VARCHAR(255)
            $table->text('profile_photo_path')->nullable(); // TEXT N (Permite nulos)
            
            // 🔒 Escudo perimetral OWASP / MFA
            $table->string('mfa_secret')->nullable(); // Almacenará la semilla criptográfica del Authenticator
            $table->boolean('is_mfa_enabled')->default(false); // Bandera de activación del doble factor
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Mantenemos la tabla de recuperación por seguridad, pero con un estándar limpio
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};