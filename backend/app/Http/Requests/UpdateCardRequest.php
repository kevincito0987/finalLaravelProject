<?php
namespace App\Http\Requests;

use App\Core\Entities\CardEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cardId = $this->route('card'); 

        return [
            // 'sometimes' asegura que solo se valide si está presente
            'uuid' => ['sometimes', 'string', 'max:36', Rule::unique('cards', 'uuid')->ignore($cardId, 'card_id')],
            'imagePath' => ['sometimes', 'required', 'string', 'max:255'],
            // IDs de claves foráneas: 'sometimes' y 'integer'
            'methodId' => ['sometimes', 'required', 'integer', 'exists:communication_methods,method_id'],
            'categoryId' => ['sometimes', 'required', 'integer', 'exists:categories,category_id'],
        ];
    }

    /**
     * Genera la Entidad de Tarjeta, pasando solo los valores que fueron enviados.
     * Los campos no enviados resultarán en NULL, lo cual la Entidad acepta.
     *
     * Nota: Los campos uuid e imagePath se inicializarán a string vacía si no se envían
     * debido al constructor de la entidad, pero el Repository debe ignorarlos si
     * la intención es no actualizar.
     */
    public function toEntity(): CardEntity
    {
        $validated = $this->validated();
        $cardId = (int) $this->route('card'); 
        
        // Usamos data_get sin valor por defecto. Si el campo no fue validado (no existe en $validated),
        // data_get devuelve NULL. Esto es clave.
        $uuid = data_get($validated, 'uuid');
        $imagePath = data_get($validated, 'imagePath');
        $methodId = data_get($validated, 'methodId');
        $categoryId = data_get($validated, 'categoryId');

        return new CardEntity(
            cardId: $cardId,
            // Aquí, si $uuid es NULL (no se envió), la Entidad pasará '' automáticamente 
            // porque el constructor CardEntity espera un 'string' y no acepta NULL para uuid/imagePath.
            // Por ello, en el Repository, DEBES IGNORAR estas cadenas vacías.
            uuid: $uuid ?? '', 
            imagePath: $imagePath ?? '',
            
            // Si $methodId es NULL, se pasa NULL (lo cual la Entidad acepta).
            // Si el Repository ve NULL, debe IGNORAR la actualización del campo.
            methodId: $methodId !== null ? (int) $methodId : null,
            categoryIdCard: $categoryId !== null ? (int) $categoryId : null
        );
    }
}
