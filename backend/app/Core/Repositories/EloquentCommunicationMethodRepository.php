<?php

namespace App\Core\Repositories;

use App\Core\Entities\CommunicationMethod;
use App\Core\Interfaces\CommunicationMethodRepositoryInterface;
use App\Models\CommunicationMethod as EloquentCommunicationMethodModel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Implementación concreta del repositorio usando Eloquent (Laravel).
 * Convierte modelos de Eloquent a Entidades de Dominio y viceversa.
 */
class EloquentCommunicationMethodRepository implements CommunicationMethodRepositoryInterface
{
    /**
     * Mapea un modelo de Eloquent a una Entidad de Dominio.
     */
    private function mapToEntity(EloquentCommunicationMethodModel $model): CommunicationMethod
    {
        // Solución al TypeError: Nos aseguramos de que method_name se convierta a string
        return new CommunicationMethod(
            $model->method_id,
            (string) $model->method_name // Forzamos la conversión a string
        );
    }

    public function getAll(): Collection
    {
        return EloquentCommunicationMethodModel::all()->map(function ($model) {
            return $this->mapToEntity($model);
        });
    }

    public function getById(int $id): ?CommunicationMethod
    {
        $model = EloquentCommunicationMethodModel::find($id);
        
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(CommunicationMethod $method): CommunicationMethod
    {
        $model = EloquentCommunicationMethodModel::create([
            'method_name' => $method->methodName,
        ]);

        return $this->mapToEntity($model);
    }

    public function update(CommunicationMethod $method): CommunicationMethod
    {
        try {
            $model = EloquentCommunicationMethodModel::findOrFail($method->methodId); 
            
            $model->update([
                'method_name' => $method->methodName,
            ]);

            return $this->mapToEntity($model);

        } catch (ModelNotFoundException $e) {
            // Lanza una excepción más genérica para que el Service pueda manejarla
            throw new \Exception('Communication method not found for update.', 404); 
        }
    }

    public function delete(int $id): bool
    {
        return EloquentCommunicationMethodModel::destroy($id) > 0;
    }
}
