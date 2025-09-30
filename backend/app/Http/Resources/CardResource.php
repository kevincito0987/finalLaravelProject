<?php

namespace App\Http\Resources;

use App\Core\Entities\CardEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            // Aseguramos que si los nombres no existen en el array, sean null
            'categoryName' => $data['categoryName'] ?? null,
            'methodName' => $data['methodName'] ?? null,
        ];
    }
}
