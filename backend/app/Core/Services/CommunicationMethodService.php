<?php

namespace App\Core\Services;

use App\Core\Entities\CommunicationMethod;
use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use Illuminate\Support\Collection;
use App\Core\Exceptions\NotFoundException; // Asumo que tienes una excepción base para no encontrados

/**
 * Clase de servicio que encapsula la lógica de negocio para los Métodos de Comunicación.
 * Utiliza la interfaz de repositorio (Dependency Inversion Principle).
 */
class CommunicationMethodService
{
    protected CommunicationMethodRepositoryInterface $repository;

    public function __construct(CommunicationMethodRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Obtiene todos los métodos de comunicación.
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Obtiene un método por su ID.
     * @param int $id
     * @return CommunicationMethod
     * @throws NotFoundException
     */
    public function getById(int $id): CommunicationMethod
    {
        $method = $this->repository->getById($id);
        
        if (!$method) {
            // Lanza una excepción de dominio si el recurso no existe
            throw new \Exception('Communication method not found.', 404); 
            // Si tuvieras App\Core\Exceptions\NotFoundException, úsala aquí.
        }

        return $method;
    }

    /**
     * Crea un nuevo método.
     * @param string $methodName
     * @return CommunicationMethod
     */
    public function create(string $methodName): CommunicationMethod
    {
        // Se crea una nueva entidad de dominio. Se usa 0 o null como ID temporal.
        $newMethod = new CommunicationMethod(0, $methodName);
        
        return $this->repository->create($newMethod);
    }

    /**
     * Actualiza un método existente.
     * @param int $methodId
     * @param string $methodName
     * @return CommunicationMethod
     * @throws NotFoundException
     */
    public function update(int $methodId, string $methodName): CommunicationMethod
    {
        // Se podría validar primero que el método exista con $this->getById($methodId);
        // Por simplicidad, se deja que el repositorio maneje el error de no encontrado.

        $methodToUpdate = new CommunicationMethod($methodId, $methodName);
        
        return $this->repository->update($methodToUpdate);
    }

    /**
     * Elimina un método.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
