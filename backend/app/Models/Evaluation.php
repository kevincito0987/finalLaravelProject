<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * schema="Evaluation",
 * title="Evaluation",
 * description="Representa la evaluación asociada a una lección. Esta tabla contiene la estructura base de la evaluación.",
 * @OA\Property(property="evaluation_id", type="integer", description="ID de la evaluación (Clave Primaria).", example=1),
 * @OA\Property(property="lesson_id_evaluation", type="integer", description="ID de la Lección asociada (Clave Foránea).", example=101),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación."),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización.")
 * )
 */
class Evaluation extends Model
{
    use HasFactory;

    // 1. Configuración de la tabla
    protected $table = 'evaluations';

    // La clave primaria no es 'id', sino 'evaluation_id'
    protected $primaryKey = 'evaluation_id';

    // 2. Campos que se pueden asignar masivamente
    protected $fillable = [
        'lesson_id_evaluation',
    ];

    // 3. Relaciones

    /**
     * Define la relación: Una evaluación pertenece a una lección.
     * CRÍTICO: El tercer argumento ('lesson_id') especifica la clave primaria de la tabla 'lessons'.
     * Asume que el modelo Lesson existe y utiliza 'lesson_id' como PK.
     */
    public function lesson(): BelongsTo
    {
        // Clave foránea en 'evaluations' | Clave local en 'lessons'
        return $this->belongsTo(Lesson::class, 'lesson_id_evaluation', 'lesson_id');
    }
}
