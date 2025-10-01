<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon; // Importamos Carbon

/**
 * @property int $progress_id
 * @property int $user_id_progress
 * @property int $card_id_progress
 * @property int $use_count
 * @property Carbon|null $last_used_at // Tipado a Carbon
 */
class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';
    protected $primaryKey = 'progress_id';
    public $incrementing = true;
    public $timestamps = false; // Desactivamos created_at y updated_at

    protected $fillable = [
        'user_id_progress',
        'card_id_progress',
        'use_count',
        'last_used_at',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     * Es crucial que 'last_used_at' se casteé a 'datetime' para que Eloquent
     * lo convierta a un objeto Carbon (o DateTime).
     * @var array<string, string>
     */
    protected $casts = [
        'last_used_at' => 'datetime',
    ];
    
    // --- Relaciones (sin cambios) ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_progress');
    }

    public function card(): BelongsTo
    {
        // Asumiendo que el modelo de tarjeta se llama LessonCard
        return $this->belongsTo(LessonCard::class, 'card_id_progress', 'card_id_evaluation');
    }
}
