<?php

namespace App\Http\Requests;

use App\Core\Entities\Lessons\LessonEntity;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para la actualización de una lección (PUT/PATCH).
 * * Este docblock interno de PHP se mantiene separado del docblock de Swagger.
 */

/**
 * @OA\Schema(
 * schema="UpdateLessonRequest",
 * title="Update Lesson Request",
 * description="Datos opcionales para actualizar una lección existente. Al menos un campo debe estar presente.",
 * @OA\Property(
 * property="lessonName",
 * type="string",
 * example="Nuevo Nombre de Introducción",
 * description="Nombre de la lección. (Opcional, Máximo 100 caracteres)"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * example="Una descripción revisada y mejorada de la lección.",
 * description="Descripción detallada de la lección. (Opcional)"
 * ),
 * @OA\Property(
 * property="lessonType",
 * type="string",
 * example="lectura",
 * description="Tipo de contenido de la lección (e.g., 'video', 'lectura'). (Opcional, Máximo 50 caracteres)"
 * )
 * )
 */
class UpdateLessonRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Todos los campos son OPCIONALES para la actualización, pero si se envían, deben ser válidos.
        return [
            'lessonName' => ['sometimes', 'string', 'max:100'],
            'description' => ['sometimes', 'string'],
            'lessonType' => ['sometimes', 'string', 'max:50'],
        ];
    }
    
    /**
     * Personaliza los nombres de atributos para los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'lessonName' => 'nombre de la lección',
            'description' => 'descripción',
            'lessonType' => 'tipo de lección',
        ];
    }

    /**
     * [NUEVO MÉTODO]
     * Convierte los datos validados del Request a un array con solo los campos presentes
     * para la actualización. Para el patrón DDD/Entity, devolvemos un objeto de Entidad.
     * @return LessonEntity
     */
    public function toEntity(): LessonEntity
    {
        // Obtiene SOLO los campos que se enviaron y pasaron la validación
        $validatedData = $this->validated();
        
        // Creamos una entidad, asumiendo que tu constructor de LessonEntity permite 
        // valores nulos o usa parámetros con nombre para omitir la lección ID.

        return new LessonEntity(
            lessonName: $validatedData['lessonName'] ?? null, // Usamos '?? null' porque son opcionales
            description: $validatedData['description'] ?? null,
            lessonType: $validatedData['lessonType'] ?? null,
            lessonId: null // El ID de la lección a actualizar se pasa por la ruta, no por el body.
        );
    }
}
