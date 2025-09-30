<?php

namespace App\Http\Requests;

use App\Core\Entities\CardEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str; // Necesitamos esta clase para generar el UUID

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
            // UUID NO ES REQUERIDO aquí; se generará automáticamente
            'uuid' => ['nullable', 'string', 'max:36', 'unique:cards,uuid'], 
            'imagePath' => ['required', 'string', 'max:255'],
            'methodId' => ['required', 'integer', 'exists:communication_methods,method_id'],
            'categoryId' => ['required', 'integer', 'exists:categories,category_id'],
        ];
    }
    
    // Sobreescribimos el método validated() para generar el UUID si falta.
    public function validated($key = null, $default = null): array
    {
        $validatedData = parent::validated($key, $default);

        // Si el UUID no se proporcionó en el request, lo generamos.
        if (!isset($validatedData['uuid']) || empty($validatedData['uuid'])) {
            $validatedData['uuid'] = (string) Str::uuid();
        }

        return $validatedData;
    }


    public function toEntity(): CardEntity
    {
        $validated = $this->validated();

        return new CardEntity(
            null, // ID nulo para creación (asumiendo que CardEntity acepta ?int)
            $validated['uuid'], // Ya se ha generado en el método validated() si era nulo
            $validated['imagePath'],
            // CORRECCIÓN: Casteamos a (int) para cumplir con el contrato de CardEntity
            (int) $validated['methodId'], 
            (int) $validated['categoryId']
        );
    }
}
