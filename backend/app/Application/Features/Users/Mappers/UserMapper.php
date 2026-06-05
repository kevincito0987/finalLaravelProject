<?php

namespace App\Application\Features\Users\Mappers;

use App\Domain\Entities\User as UserEntity;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Models\User as UserModel;

class UserMapper
{
    /**
     * Transforma un Modelo Eloquent de la base de datos en una Entidad de Dominio pura.
     */
    public static function toDomain(UserModel $model): UserEntity
    {
        // Verificamos si la relación 'role' viene cargada desde el modelo de Eloquent
        $roleEntity = null;
        if ($model->relationLoaded('role') && $model->role !== null) {
            // Invoca tu RoleMapper que está en el mismo namespace/directorio
            $roleEntity = RoleMapper::toDomain($model->role); 
        }

        return new UserEntity(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email), // 🔑 Instanciamos el Value Object Email con el string de la BD
            password: new Password($model->password, isAlreadyHashed: true), // 🔑 Pasamos el hash de la BD indicando que ya está encriptado
            profilePhotoPath: $model->profile_photo_path,
            roleId: $model->role_id,
            role: $roleEntity,
            mfaSecret: $model->mfa_secret,
            isMfaEnabled: $model->is_mfa_enabled
        );
    }

    /**
     * Transforma una Entidad de Dominio en un formato de array nativo listo para persistirse en MySQL.
     */
    public static function toPersistence(UserEntity $entity): array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'email' => $entity->getEmail()->getValue(), // 🔑 Extraemos el string primitivo del objeto Email
            'password' => $entity->getPassword()->getHash(), // 🔑 Extraemos el string del hash puro del objeto Password
            'profile_photo_path' => $entity->getProfilePhotoPath(),
            'role_id' => $entity->getRoleId(),
            'mfa_secret' => $entity->getMfaSecret(),
            'is_mfa_enabled' => $entity->isMfaEnabled(),
        ];
    }
}