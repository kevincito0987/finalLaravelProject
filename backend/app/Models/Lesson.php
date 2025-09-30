<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'lessons';

    /**
     * La clave primaria de la tabla, tal como se definió en la migración.
     * @var string
     */
    protected $primaryKey = 'lesson_id';
    
    /**
     * Indica si el ID es autoincremental.
     * @var bool
     */
    public $incrementing = true; 

    /**
     * Los atributos que son asignables masivamente.
     * Aquí es donde mapeamos de 'camelCase' (en el Factory/Request) a 'snake_case' o viceversa,
     * pero usamos los nombres de columna tal como están en la DB (snake_case por convención Laravel).
     * * Basado en la migración: lesson_id, lessonName, description, lessonType.
     * * ¡CRUCIAL! Eloquent NO puede acceder a `lessonName` si la columna se llama `lesson_name` en la DB.
     * Tu migración (paso anterior) usaba `lessonName` y `lessonType`. Mantendremos esos nombres
     * por ahora, pero **recomendamos usar snake_case en la DB** (lesson_name).
     */
    protected $fillable = [
        'lessonName', 
        'description', 
        'lessonType',
    ];
}
