<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id ID del usuario asociado al progreso.
 * @property int $lesson_id ID de la lección asociada.
 * @property \Illuminate\Support\Carbon|null $completed_at Marca de tiempo de finalización.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class UserLesson extends Model
{
    use HasFactory;

    /**
     * Define los atributos que se pueden asignar masivamente (Mass Assignment).
     * Esto resuelve el error 1364 al permitir que user_id y lesson_id se inserten.
     * * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];

    /**
     * Define la columna que se usa para el timestamp de completado como un Carbon.
     * @var array<string>
     */
    protected $dates = [
        'completed_at',
    ];

    // --- RELACIONES ---

    /**
     * Obtiene el usuario propietario de este registro de progreso.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la lección asociada a este registro de progreso.
     * * Nota: Como la clave primaria en 'lessons' es 'lesson_id' (y no 'id'),
     * necesitamos especificar la clave foránea local ('lesson_id')
     * y la clave primaria referenciada ('lesson_id').
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
