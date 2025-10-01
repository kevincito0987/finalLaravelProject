<?php

namespace App\Core\CommunicationStrategies;

use App\Core\Entities\Card\CardEntity;
use Illuminate\Support\Collection;

/**
 * Implementa la adaptación para la comunicación AUDITIVA.
 * Prioriza el texto (frase) para ser leído en voz alta o asociado a un recurso de audio.
 */
class AudioStrategy implements CommunicationStrategyInterface
{
    /**
     * Adapta una colección de CardEntity para su uso auditivo.
     * * @param Collection<CardEntity> $cards
     * @return Collection<CardEntity>
     */
    public function adapt(Collection $cards): Collection
    {
        // En el futuro, se podría inyectar un CardTranslationRepository para obtener la frase 
        // específica del idioma del usuario y adjuntarla como 'audio_prompt'.
        return $cards->map(function (CardEntity $card) {
            // Ejemplo de simulación de adaptación:
            // Si la tarjeta tuviera un campo 'audioUrl', se aseguraría que esté disponible.
            
            // Para la demostración, podríamos generar un campo extra si CardEntity lo permitiera
            // o simplemente devolver la entidad sin cambios, esperando que el frontend 
            // sepa que debe leer la frase.
            
            return $card;
        });
    }
}
