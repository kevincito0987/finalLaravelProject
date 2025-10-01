<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Asumiendo que esta es la ubicación

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    // *** CRUCIAL: Indicamos a Eloquent que la PK se llama progress_id ***
    protected $primaryKey = 'progress_id';
    
    // Indica que la clave primaria NO es un autoincremento si no lo fuera.
    // Aunque $table->id('progress_id') es autoincremental, es bueno ser explícito.
    public $incrementing = true; 

    // Claves foráneas en esta tabla: user_id, lesson_id, card_id
    protected $fillable = [
        'user_id', 
        'lesson_id', 
        'card_id', 
        'use_count', 
        'score',
        'last_used_at',
    ];

    protected $casts = [
        'use_count' => 'integer',
        'score' => 'integer',
        'last_used_at' => 'datetime',
    ];

    /**
     * Define la relación con el usuario.
     * La FK en 'user_progress' es 'user_id', y la PK en 'users' es 'user_id'.
     */
    public function user()
    {
        // belongsTo(RelatedModel, ForeignColumnInThisTable, PrimaryColumnInRelatedTable)
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    /**
     * Define la relación con la lección.
     * Asumiendo que Lesson::class usa 'lesson_id' como PK.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Define la relación con la tarjeta.
     * Asumiendo que Card::class usa 'card_id' como PK.
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }
}
