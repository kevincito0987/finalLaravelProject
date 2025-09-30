<?php

namespace App\Http\Resources;

use App\Core\Entities\CardEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * schema="CardResource",
 * title="Card Resource",
 * description="Representación de una tarjeta de comunicación (Card) con sus datos de identificación y relaciones.",
 * @OA\Property(property="cardId", type="integer", example=52, description="ID primario de la tarjeta."),
 * @OA\Property(property="uuid", type="string", format="uuid", example="8a0e0a9e-9c19-4d50-9e91-54dae0705053", description="UUID único de la tarjeta."),
 * @OA\Property(property="imagePath", type="string", example="placeholders/card_image_52.jpg", description="Ruta o URL de la imagen."),
 * @OA\Property(property="methodId", type="integer", example=2, description="ID del método de comunicación."),
 * @OA\Property(property="categoryIdCard", type="integer", example=2, description="ID de la categoría."),
 * @OA\Property(property="categoryName", type="string", nullable=true, example="Sentimientos", description="Nombre de la categoría."),
 * @OA\Property(property="methodName", type="string", nullable=true, example="Auditivo", description="Nombre del método de comunicación."),
 * @OA\Property(property="consecutiveId", type="integer", nullable=true, example=1, description="ID consecutivo/índice de ordenamiento. Solo presente en listados (index).")
 * )
 */
class CardResource extends JsonResource
{
    /**
     * @var CardEntity|array La entidad o el array de datos que estamos transformando
     */
    public $resource; 

    // Opcional: Remueve el constructor si solo usas la clase por defecto (self::$wrap)
    // public function __construct($resource) 
    // {
    //     parent::__construct($resource);
    // }

    /**
     * Transforma el recurso (Entidad o Array) en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Determinamos el origen de los datos
        if ($this->resource instanceof CardEntity) {
            // Caso 1: Es una entidad individual (POST de creación o GET por ID)
            $data = $this->resource->toArray();
            $consecutiveId = null; // No hay ID consecutivo para una entidad individual
        } else {
            // Caso 2: Es un array (Viene de CardService::getCards() que ya inyectó el índice)
            $data = $this->resource;
            // Usamos data['consecutiveId'] si existe, si no, null
            $consecutiveId = $data['consecutiveId'] ?? null; 
        }

        return [
            // El ID consecutivo solo tendrá valor en el listado (Caso 2)
            'consecutiveId' => $consecutiveId,

            // Mapeo de Entidad/Array a JSON de salida (usando el array $data)
            'cardId' => $data['cardId'], 
            'uuid' => $data['uuid'],
            'imagePath' => $data['imagePath'],
            
            // IDs de Foráneas
            'methodId' => $data['methodId'],
            'categoryIdCard' => $data['categoryIdCard'],
            
            // Nombres de Relaciones
            // Aseguramos que los nombres no existen en el array, sean null
            'categoryName' => $data['categoryName'] ?? null,
            'methodName' => $data['methodName'] ?? null,
        ];
    }
}
