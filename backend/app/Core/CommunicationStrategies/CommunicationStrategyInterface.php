<?php

namespace App\Core\CommunicationStrategies;

use App\Core\Entities\Card\CardEntity;
use Illuminate\Support\Collection;

/**
 * Define el contrato para adaptar una tarjeta a un método de comunicación específico.
 */
interface CommunicationStrategyInterface
{
    /**
     * Adapta una colección de CardEntity al formato de comunicación de la estrategia.
     * * @param Collection<CardEntity> $cards
     * @return Collection<CardEntity>
     */
    public function adapt(Collection $cards): Collection;
}
