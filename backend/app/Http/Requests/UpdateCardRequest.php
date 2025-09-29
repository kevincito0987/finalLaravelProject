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
            // UUID debe ser único, excluyendo la tarjeta actual
            'uuid' => ['sometimes', 'required', 'string', 'max:36', Rule::unique('cards', 'uuid')->ignore($cardId, 'card_id')],
            'imagePath' => ['sometimes', 'required', 'string', 'max:255'],
            'phrase' => ['sometimes', 'required', 'string', 'max:255'],
            'audioPath' => ['nullable', 'string', 'max:255'],
            'methodId' => ['sometimes', 'required', 'integer', 'exists:communication_methods,method_id'],
            'categoryId' => ['sometimes', 'required', 'integer', 'exists:categories,category_id'],
        ];
    }

    public function toEntity(): CardEntity
    {
        $validated = $this->validated();
        $cardId = $this->route('card'); 
        
        // Se usa la función 'data_get' para proporcionar un valor predeterminado 
        // si el campo no está presente en la solicitud (PUT/PATCH).
        return new CardEntity(
            $cardId,
            data_get($validated, 'uuid', ''),
            data_get($validated, 'imagePath', ''),
            data_get($validated, 'phrase', ''),
            data_get($validated, 'audioPath', null),
            data_get($validated, 'methodId', 0),
            data_get($validated, 'categoryId', 0)
        );
    }
}