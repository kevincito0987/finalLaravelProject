<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use OpenApi\Annotations as OA;

class LessonCard extends Pivot
{
    // Nombre de la tabla
    protected $table = 'lesson_cards';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'lesson_id',
        'card_id', // <-- Campo de la clave foránea
        'order_in_lesson',
    ];

    public $incrementing = true;
    
    // RELACIÓN CLAVE: Esta es la relación que debemos cargar de forma anidada
    public function card() 
    {
        // El nombre de esta función (card) es la clave anidada
        return $this->belongsTo(Card::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
