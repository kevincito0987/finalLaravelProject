<?php

namespace App\Core\CommunicationStrategies;

use App\Core\Entities\Card\CardEntity;
use Illuminate\Support\Collection;

/**
 * Implementa la adaptación para la comunicación VISUAL.
 * Asegura que se priorice la ruta de la imagen.
 */
class VisualStrategy implements CommunicationStrategyInterface
{
    /**
     * Adapta una colección de CardEntity para su visualización.
     * En este caso, simplemente asegura que el campo imagePath esté listo.
     * * @param Collection<CardEntity> $cards
     * @return Collection<CardEntity>
     */
    public function adapt(Collection $cards): Collection
    {
        // El contenido visual es el predeterminado, por lo que la adaptación es mínima.
        // Podríamos, por ejemplo, aplicar una compresión o marca de agua si fuera necesario, 
        // pero por ahora, solo devolvemos las tarjetas.
        return $cards->map(function (CardEntity $card) {
            // Ejemplo: Asegurar que imagePath es una URL completa
            // $card->imagePath = asset('storage/' . $card->imagePath);

            // Nota: Para implementar cambios en la Entidad, necesitarías que sus propiedades no sean readonly
            // o crear una nueva instancia de la Entidad. Por simplicidad, asumiremos que el frontend
            // leerá el campo imagePath directamente.
            return $card;
        });
    }
}
