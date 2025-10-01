<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $progress_id
 * @property int $user_id_progress
 * @property int $card_id_progress
 * @property int $use_count
 * @property string $last_used_at
 */
class UserProgress extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla de la base de datos.
     * @var string
     */
    protected $table = 'user_progress';

    /**
     * Nombre de la clave primaria.
     * @var string
     */
    protected $primaryKey = 'progress_id';

    /**
     * Las claves primarias no son auto-incrementables por defecto (aunque en el
     * diagrama es INT, asumimos que sí lo es para un modelo Laravel estándar).
     * @var bool
     */
    public $incrementing = true;

    /**
     * Los atributos que son asignables masivamente (fillable).
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id_progress',
        'card_id_progress',
        'use_count',
        'last_used_at',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     * last_used_at se maneja como fecha.
     * @var array<string, string>
     */
    protected $casts = [
        'last_used_at' => 'datetime',
    ];
    
    // Desactivamos 'created_at' y 'updated_at' si la tabla no las tiene (que parece ser el caso)
    public $timestamps = false; 

    // --- Relaciones ---

    /**
     * Relación: Un progreso pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        // Asumiendo que tu tabla de usuarios usa 'id' como PK
        return $this->belongsTo(User::class, 'user_id_progress');
    }

    /**
     * Relación: Un progreso pertenece a una tarjeta de lección.
     */
    public function card(): BelongsTo
    {
        // Se asume que el modelo de tarjeta se llama LessonCard
        // y que su clave primaria es 'card_id_evaluation' (según el diagrama de preguntas)
        return $this->belongsTo(LessonCard::class, 'card_id_progress', 'card_id_evaluation');
    }
}
