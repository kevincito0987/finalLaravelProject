<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;

    protected $primaryKey = 'card_id';

    protected $fillable = [
        'uuid',
        'image_path',
        'phrase',
        'audio_path',
        'method_id',
        'category_id_card',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id_card', 'category_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(CommunicationMethod::class, 'method_id', 'method_id');
    }
}