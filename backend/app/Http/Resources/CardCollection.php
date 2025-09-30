<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 * schema="CardCollection",
 * title="Card Collection",
 * description="Estructura de la respuesta para obtener una lista de tarjetas.",
 * @OA\Property(
 * property="data",
 * type="array",
 * description="Lista de recursos de tarjeta individuales.",
 * @OA\Items(ref="#/components/schemas/CardResource")
 * ),
 * @OA\Property(
 * property="meta",
 * type="object",
 * description="Metadata de paginación (si se implementa), omitido si no hay paginación."
 * )
 * )
 */
class CardCollection extends ResourceCollection
{
    /**
     * Transforma la colección de recursos en un array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // El 'data' contendrá una colección de CardResource mapeados.
            'data' => $this->collection,
            // Aquí puedes agregar metadata para la paginación si la implementas después
            // 'meta' => [ ... ] 
        ];
    }
}
