<?php

namespace App\Core\Interfaces;

use App\Core\Entities\CommunicationMethodEntity;
use Illuminate\Support\Collection;

/**
 * Interfaz que define las operaciones CRUD para los métodos de comunicación.
 * Es parte de la capa Core, por lo que no debe depender de Eloquent.
 */
interface CommunicationMethodRepositoryInterface
{
    /**
     * Obtiene todos los métodos de comunicación.
     * @return Collection<CommunicationMethodEntity>
     */
    public function getAll(): Collection;

    /**
     * Encuentra un método por su ID.
     * @param int $id
     * @return CommunicationMethodEntity|null
     */
    public function findById(int $id): ?CommunicationMethodEntity;

    /**
     * Guarda o actualiza un método de comunicación.
     * @param CommunicationMethodEntity $method
     * @return CommunicationMethodEntity
     */
    public function save(CommunicationMethodEntity $method): CommunicationMethodEntity;

    /**
     * Elimina un método por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
