<?php

namespace App\Core\Interfaces;

use App\Core\Entities\CommunicationMethod;
use Illuminate\Support\Collection;

/**
 * Define el contrato para la persistencia de datos de los Métodos de Comunicación.
 * Esta interfaz vive en el Core (Capa de Dominio).
 */
interface CommunicationMethodRepositoryInterface
{
    /**
     * Obtiene todos los métodos de comunicación.
     * @return Collection<CommunicationMethod>
     */
    public function getAll(): Collection;

    /**
     * Obtiene un método de comunicación por su ID.
     * @param int $id
     * @return CommunicationMethod|null
     */
    public function getById(int $id): ?CommunicationMethod;

    /**
     * Crea un nuevo método de comunicación.
     * @param CommunicationMethod $method
     * @return CommunicationMethod
     */
    public function create(CommunicationMethod $method): CommunicationMethod;

    /**
     * Actualiza un método de comunicación existente.
     * @param CommunicationMethod $method
     * @return CommunicationMethod
     */
    public function update(CommunicationMethod $method): CommunicationMethod;

    /**
     * Elimina un método de comunicación por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
