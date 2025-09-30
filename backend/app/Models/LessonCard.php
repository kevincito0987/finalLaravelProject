<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * schema="LessonCard",
 * title="Lesson Card Association",
 * description="Representa la asociación entre una Lección y una Tarjeta, incluyendo su orden.",
 * @OA\Property(property="id", type="integer", description="ID primario del registro de asociación.", example=1),
 * @OA\Property(property="lesson_id", type="integer", description="ID de la Lección asociada.", example=101),
 * @OA\Property(property="card_id", type="integer", description="ID de la Tarjeta asociada.", example=205),
 * @OA\Property(property="order_in_lesson", type="integer", description="Orden de la tarjeta dentro de la lección.", example=3),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación."),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización.")
 * )
 */
class LessonCard extends Pivot
{
    // Nombre de la tabla
    protected $table = 'lesson_cards';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'lesson_id',
        'card_id',
        'order_in_lesson',
    ];

    // Indica que la tabla usa una clave primaria autoincremental por defecto (id)
    public $incrementing = true;
    
    // Define las relaciones (aunque no se usan directamente para la documentación de esquema, son buenas prácticas)
    public function lesson()
    {
        // Asumiendo que el modelo Lesson existe
        return $this->belongsTo(Lesson::class);
    }

    public function card()
    {
        // Asumiendo que el modelo Card existe
        return $this->belongsTo(Card::class);
    }
}
