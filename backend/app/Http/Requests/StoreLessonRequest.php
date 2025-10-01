<?php

namespace App\Http\Requests;

use App\Core\Entities\Lessons\LessonEntity;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * schema="StoreLessonRequest",
 * title="Store Lesson Request",
 * description="Datos requeridos para crear una nueva lección.",
 * required={"lessonName", "description", "lessonType"},
 * @OA\Property(
 * property="lessonName",
 * type="string",
 * example="Introducción a la Terapia",
 * description="Nombre de la lección. (Máximo 100 caracteres)"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * example="Lección básica sobre conceptos fundamentales de la terapia.",
 * description="Descripción detallada de la lección."
 * ),
 * @OA\Property(
 * property="lessonType",
 * type="string",
 * example="video",
 * description="Tipo de contenido de la lección (e.g., 'video', 'lectura'). (Máximo 50 caracteres)"
 * )
 * )
 */
class StoreLessonRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Generalmente, se verifica que el usuario esté autenticado.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Todos los campos son OBLIGATORIOS para la creación
        return [
            'lessonName' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'lessonType' => ['required', 'string', 'max:50'], 
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
     * Convierte los datos validados del Request a una LessonEntity.
     * Esto centraliza la lógica de mapeo.
     * @return LessonEntity
     */
    public function toEntity(): LessonEntity
    {
        // Usa el método validated() para obtener solo los datos que pasaron la validación
        $validated = $this->validated();

        return new LessonEntity(
            // El orden es importante: lessonName, description, lessonType, lessonId=null
            lessonName: $validated['lessonName'],
            description: $validated['description'],
            lessonType: $validated['lessonType'],
            lessonId: null // Siempre es null al crear una nueva entidad
        );
    }
}
