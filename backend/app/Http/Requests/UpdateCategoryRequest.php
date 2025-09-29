<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Core\Entities\CategoryEntity;
use Illuminate\Validation\Rule;

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