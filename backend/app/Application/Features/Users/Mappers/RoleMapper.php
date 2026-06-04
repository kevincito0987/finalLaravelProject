<?php

namespace App\Application\Features\Users\Mappers;

use App\Domain\Entities\Role as DomainRole;
use App\Models\Role as EloquentRole;

class RoleMapper
{
    /**
     * Transforma un Modelo de Eloquent de la BD a una Entidad Pura de Dominio
     */
    public static function toDomain(EloquentRole $eloquentRole): DomainRole
    {
        return new DomainRole(
            id: $eloquentRole->id,
            name: $eloquentRole->name
        );
    }

    /**
     * Transforma una Entidad de Dominio a un Modelo Eloquent listo para guardar en la BD
     */
    public static function toEloquent(DomainRole $domainRole): EloquentRole
    {
        $eloquentRole = new EloquentRole();
        
        if ($domainRole->getId() !== null) {
            $eloquentRole->id = $domainRole->getId();
            $eloquentRole->exists = true; // Le avisa a Laravel que es un update y no un insert
        }
        
        $eloquentRole->name = $domainRole->getName();
        
        return $eloquentRole;
    }
}