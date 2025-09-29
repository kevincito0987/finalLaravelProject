<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para la tabla 'communication_methods'.
 * Mantiene la lógica de persistencia y la configuración de la DB.
 */
class CommunicationMethod extends Model
{
    use HasFactory;

    protected $table = 'communication_methods';
    protected $primaryKey = 'method_id'; // Clave primaria
    public $incrementing = true;
    public $timestamps = false; // Desactivar created_at y updated_at

    protected $fillable = [
        'method_name',
    ];
}
