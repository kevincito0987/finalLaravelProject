<?php

namespace App\Http\Requests;

use App\Core\Entities\Card\CardEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str; // Necesitamos esta clase para generar el UUID

/**
 * @OA\Schema(
 * schema="StoreCardRequest",
 * title="Store Card Request",
 * description="Datos necesarios para crear una nueva Card. El 'uuid' es opcional y se genera si no se proporciona.",
 * required={"imagePath", "methodId", "categoryId"},
 * @OA\Property(property="uuid", type="string", format="uuid", nullable=true, description="UUID único de la tarjeta (se genera automáticamente si no se envía).", example="a1b2c3d4-e5f6-7890-1234-567890abcdef"),
 * @OA\Property(property="imagePath", type="string", description="Ruta o URL de la imagen de la tarjeta.", example="images/nueva_card.png"),
 * @OA\Property(property="methodId", type="integer", description="ID del método de comunicación.", example=1),
 * @OA\Property(property="categoryId", type="integer", description="ID de la categoría.", example=3)
 * )
 */
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
