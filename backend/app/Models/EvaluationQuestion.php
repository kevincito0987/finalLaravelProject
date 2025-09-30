<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Eloquent para la tabla 'evaluation_questions'.
 * Su única responsabilidad es la persistencia en la DB.
 */
class EvaluationQuestion extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'evaluation_questions'; 

    // Clave primaria
    protected $primaryKey = 'question_id'; 
    public $incrementing = true; 
    
    // *** LA LÍNEA MÁGICA PARA SOLUCIONAR EL ERROR DE "updated_at" ***
    // Esto le dice a Eloquent que la tabla no tiene las columnas created_at y updated_at.
    public $timestamps = false; 

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'evaluation_id_question', 
        'card_id_evaluation', 
        'question_text', 
        'correct_answer', 
        'options',
    ];

    // Casteos para que 'options' se maneje como array en PHP
    protected $casts = [
        'options' => 'array',
    ];

    // Opcional: Definir relaciones si es necesario
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id_question');
    }

    public function card()
    {
        // Nota: Si la tabla referenciada es 'cards', el modelo debe ser Card.
        // Si tu modelo se llama LessonCard, asegúrate que la tabla referenciada 
        // en la migración sea 'lesson_cards' o 'cards' según corresponda.
        return $this->belongsTo(LessonCard::class, 'card_id_evaluation');
    }
} // Agregué el punto y coma final para consistencia
