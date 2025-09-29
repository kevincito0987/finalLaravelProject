<?php

namespace App\Http\Requests;

use App\Core\Entities\CardEntity;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El middleware de rol ya protege esta ruta (therapist/admin)
        return true; 
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'string', 'max:36', 'unique:cards,uuid'],
            'imagePath' => ['required', 'string', 'max:255'],
            'phrase' => ['required', 'string', 'max:255'],
            'audioPath' => ['nullable', 'string', 'max:255'],
            'methodId' => ['required', 'integer', 'exists:communication_methods,method_id'],
            'categoryId' => ['required', 'integer', 'exists:categories,category_id'],
        ];
    }

    public function toEntity(): CardEntity
    {
        $validated = $this->validated();

        return new CardEntity(
            null, // ID nulo para creación
            $validated['uuid'],
            $validated['imagePath'],
            $validated['phrase'],
            $validated['audioPath'] ?? null,
            $validated['methodId'],
            $validated['categoryId']
        );
    }
}