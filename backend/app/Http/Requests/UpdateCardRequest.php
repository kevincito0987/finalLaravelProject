<?php
namespace App\Http\Requests;

use App\Core\Entities\CardEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="UpdateCardRequest",
 * title="Update Card Request",
 * description="Datos opcionales para actualizar una Card existente. Utiliza la lógica 'sometimes' para permitir actualizaciones parciales (PATCH).",
 * @OA\Property(property="uuid", type="string", format="uuid", nullable=true, description="UUID único de la tarjeta. Solo se valida si se envía.", example="a1b2c3d4-e5f6-7890-1234-567890abcdef"),
 * @OA\Property(property="imagePath", type="string", nullable=true, description="Ruta o URL de la imagen de la tarjeta.", example="images/nueva_card_v2.png"),
 * @OA\Property(property="methodId", type="integer", nullable=true, description="ID del método de comunicación.", example=2),
 * @OA\Property(property="categoryId", type="integer", nullable=true, description="ID de la categoría.", example=5)
 * )
 */
class UpdateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El middleware de rol ya protege esta ruta (therapist/admin)
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
