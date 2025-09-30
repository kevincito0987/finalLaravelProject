<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Core\Entities\CategoryEntity;

/**
 * @OA\Schema(
 * schema="StoreCategoryRequest",
 * title="Store Category Request",
 * description="Datos necesarios para crear una nueva categoría.",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre único de la categoría. Se mapea a la columna 'category_name' en la base de datos.",
 * example="Lugares"
 * )
 * )
 */
class StoreCategoryRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            // 'category_name' en la DB, pero lo nombramos 'name' en el request por convención
            'name' => 'required|string|max:100|unique:categories,category_name', 
        ];
    }
    
    public function toEntity(): CategoryEntity
    {
        return new CategoryEntity($this->validated('name'));
    }
}
