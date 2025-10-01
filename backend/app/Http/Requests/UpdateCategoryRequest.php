<?php
namespace App\Http\Requests;

use App\Core\Entities\Card\CategoryEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="UpdateCategoryRequest",
 * title="Update Category Request",
 * description="Datos necesarios para actualizar el nombre de una categoría existente.",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nuevo nombre único de la categoría. Se mapea a la columna 'category_name' en la base de datos.",
 * example="Emociones"
 * )
 * )
 */
class UpdateCategoryRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        // Obtenemos el ID de la categoría que se está editando desde la ruta
        $categoryId = $this->route('category'); 
        
        return [
            // El campo debe ser único, EXCEPTO para el registro que estamos editando
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'category_name')->ignore($categoryId, 'category_id'),
            ],
        ];
    }
    
    public function toEntity(): CategoryEntity
    {
        // El ID se pasa al servicio directamente desde la ruta en el controlador, la entidad solo necesita el nombre
        return new CategoryEntity($this->validated('name'));
    }
}
