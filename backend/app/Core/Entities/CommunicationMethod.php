<?php

namespace App\Core\Entities;

/**
 * Entidad de dominio que representa un método de comunicación.
 * Es el objeto central de la lógica de negocio.
 */
class CommunicationMethod
{
    // El ID debe ser nullable (o 0) porque una entidad nueva no lo tendrá
    public ?int $methodId; 
    public string $methodName;

    /**
     * @param int|null $methodId El ID de la BDD (puede ser null al crear)
     * @param string $methodName
     */
    public function __construct(?int $methodId, string $methodName) // Cambiamos el tipo a ?int
    {
        $this->methodId = $methodId;
        $this->methodName = $methodName;
    }

    /**
     * Devuelve los datos de la entidad como un array, útil para respuestas JSON.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'method_id' => $this->methodId,
            'method_name' => $this->methodName,
        ];
    }
}
