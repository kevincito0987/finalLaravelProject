<?php

namespace App\Core\CommunicationStrategies;

use App\Core\Entities\CardEntity;
use Illuminate\Support\Collection;

/**
 * Implementa la adaptación para la comunicación TÁCTIL o Háptica.
 * Se enfoca en proporcionar datos textuales para un lector de pantalla o 
 * datos de vibración (no implementados aquí).
 */
class TactileStrategy implements CommunicationStrategyInterface
{
    /**
     * Adapta una colección de CardEntity para dispositivos táctiles/hápticos.
     * * @param Collection<CardEntity> $cards
     * @return Collection<CardEntity>
     */
    public function adapt(Collection $cards): Collection
    {
        return $cards->map(function (CardEntity $card) {
            // Lógica: Asegurar que haya una descripción de texto útil para lectores de pantalla.
            // Si tu CardEntity tuviera un campo 'tactileDescription', lo podríamos priorizar.
            
            // Ejemplo: Podríamos modificar la Entidad para añadir un campo 'altText'
            // basado en la phrase de la traducción.
            
            return $card;
        });
    }
}
