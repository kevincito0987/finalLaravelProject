<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'profile_photo_path',
        'mfa_secret',
        'is_mfa_enabled',
    ];

    /**
     * Atributos ocultos para la serialización (Protección OWASP estándar).
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

    /**
     * Casteo de atributos nativos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_mfa_enabled' => 'boolean',
        // ⚠️ Nota: Se remueve 'password' => 'hashed' para delegar el control criptográfico al Password Value Object.
    ];

    /**
     * Relación: Un Usuario pertenece a un Rol
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}