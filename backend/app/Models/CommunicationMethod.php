<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para la tabla 'communication_methods'.
 * Usado para mapear datos de la base de datos a objetos de Laravel.
 */
class CommunicationMethod extends Model
{
    use HasFactory;

    protected $table = 'communication_methods';

    protected $primaryKey = 'method_id';

    public $incrementing = true;

    protected $fillable = [
        'method_name',
    ];

    /**
     * Define la relación Uno a Muchos con la tabla 'cards'.
     * Un método de comunicación puede tener muchas tarjetas.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        // Asumiendo que la clave foránea en la tabla 'cards' es 'method_id'
        return $this->hasMany(Card::class, 'method_id', 'method_id');
    }
}
