<?php

namespace App\Core\Entities;

/**
 * Entidad de negocio para un Método de Comunicación.
 * Esta clase es independiente de Laravel y la base de datos.
 */
class CommunicationMethodEntity
{
    public function __construct(
        public ?int $id, // Puede ser null si es una nueva entidad
        public string $name
    ) {}
}
