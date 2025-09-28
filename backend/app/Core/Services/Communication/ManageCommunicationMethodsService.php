<?php

namespace App\Core\Services\Communication;

use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Core\Entities\CommunicationMethodEntity;
use Illuminate\Support\Collection;

/**
 * Servicio de aplicación para gestionar los métodos de comunicación.
 * Orquesta las operaciones de negocio utilizando el Repositorio.
 */
class ManageCommunicationMethodsService
{
    public function __construct(
        private readonly CommunicationMethodRepositoryInterface $repository
    ) {}

    /**
     * Obtiene todos los métodos de comunicación.
     * @return Collection<CommunicationMethodEntity>
     */
    public function getMethods(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Crea un nuevo método de comunicación.
     * @param string $name
     * @return CommunicationMethodEntity
     */
    public function createMethod(string $name): CommunicationMethodEntity
    {
        $entity = new CommunicationMethodEntity(
            id: null,
            name: $name
        );

        // La lógica de validación de unicidad se maneja en la capa de persistencia/BD, 
        // o se puede agregar aquí una verificación adicional antes de guardar si es necesario.

        return $this->repository->save($entity);
    }
    
    /**
     * Actualiza un método existente.
     * @param int $id
     * @param string $name
     * @return CommunicationMethodEntity|null
     */
    public function updateMethod(int $id, string $name): ?CommunicationMethodEntity
    {
        $entity = $this->repository->findById($id);

        if (!$entity) {
            return null; // O lanzar una excepción de negocio (e.g., NotFoundException)
        }

        $entity->name = $name;

        return $this->repository->save($entity);
    }

    /**
     * Elimina un método.
     * @param int $id
     * @return bool
     */
    public function deleteMethod(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
