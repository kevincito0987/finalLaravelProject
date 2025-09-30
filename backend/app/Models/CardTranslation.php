<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardTranslation extends Model
{
    use HasFactory;

    protected $table = 'card_translations';
    protected $primaryKey = 'card_translation_id';

    // Desactivamos los timestamps si no los estás usando (o déjalos si sí)
    public $timestamps = true; 
    
    protected $fillable = [
        'card_id_translation',
        'language_code',
        'key_phrase',
        'audio_path',
    ];

    /**
     * Relación: Una traducción pertenece a una tarjeta.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id_translation', 'card_id');
    }
}
