<?php

namespace App\Core\Repositories;

use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Core\Entities\CommunicationMethodEntity;
use Illuminate\Support\Collection;
use App\Models\CommunicationMethod;
/**
 * Implementación del Repositorio de Métodos de Comunicación usando Eloquent ORM.
 * Esta clase mapea entre Modelos Eloquent y Entidades de Core.
 */
class EloquentCommunicationMethodRepository implements CommunicationMethodRepositoryInterface
{
    /**
     * Convierte un Modelo Eloquent a una Entidad de Core.
     * @param CommunicationMethod $model
     * @return CommunicationMethodEntity
     */
    private function toEntity(CommunicationMethod $model): CommunicationMethodEntity
    {
        return new CommunicationMethodEntity(
            id: $model->method_id,
            name: $model->method_name
        );
    }

    /**
     * Obtiene todos los métodos.
     * @return Collection<CommunicationMethodEntity>
     */
    public function getAll(): Collection
    {
        return CommunicationMethod::all()->map(fn ($model) => $this->toEntity($model));
    }

    /**
     * Encuentra un método por su ID.
     * @param int $id
     * @return CommunicationMethodEntity|null
     */
    public function findById(int $id): ?CommunicationMethodEntity
    {
        $model = CommunicationMethod::find($id);
        
        return $model ? $this->toEntity($model) : null;
    }

    /**
     * Guarda o actualiza un método de comunicación.
     * @param CommunicationMethodEntity $entity
     * @return CommunicationMethodEntity
     */
    public function save(CommunicationMethodEntity $entity): CommunicationMethodEntity
    {
        if ($entity->id) {
            // Actualizar
            $model = CommunicationMethod::find($entity->id);
            if (!$model) {
                // Lanza una excepción si el ID no existe (opcional, el servicio podría manejar esto)
                throw new \Exception("Communication Method with ID {$entity->id} not found.");
            }
        } else {
            // Crear
            $model = new CommunicationMethod();
        }

        $model->method_name = $entity->name;
        $model->save();

        // Asegurar que la entidad devuelta contenga el ID (si fue una creación)
        $entity->id = $model->method_id; 

        return $entity;
    }

    /**
     * Elimina un método por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return CommunicationMethod::destroy($id) > 0;
    }
}
