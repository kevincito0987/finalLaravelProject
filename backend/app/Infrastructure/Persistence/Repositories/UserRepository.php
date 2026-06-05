<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\User as UserEntity;
use App\Domain\RepositoriesInterfaces\IUserRepository;
use App\Domain\ValueObjects\Email;
use App\Models\User as UserModel;
use App\Application\Features\Users\Mappers\UserMapper;
use InvalidArgumentException;

class UserRepository implements IUserRepository
{
    public function findById(int $id): ?UserEntity
    {
        $model = UserModel::with('role')->find($id);
        return $model ? UserMapper::toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $model = UserModel::with('role')->where('email', $email->getValue())->first();
        return $model ? UserMapper::toDomain($model) : null;
    }

    public function create(UserEntity $user): UserEntity
    {
        $data = UserMapper::toPersistence($user);
        
        // Nos aseguramos de remover el ID si va en null para que MySQL maneje el auto_increment
        unset($data['id']); 

        $model = UserModel::create($data);

        return UserMapper::toDomain($model);
    }

    public function update(UserEntity $user): UserEntity
    {
        // Buscamos el modelo en la base de datos usando el ID de la entidad
        $model = UserModel::find($user->getId());

        if (!$model) {
            throw new InvalidArgumentException("No se puede actualizar. El usuario con ID {$user->getId()} no existe.");
        }

        $data = UserMapper::toPersistence($user);
        
        // Actualizamos los campos físicos en MySQL
        $model->update($data);

        return UserMapper::toDomain($model);
    }

    public function delete(int $id): bool
    {
        $model = UserModel::find($id);
        return $model ? (bool) $model->delete() : false;
    }
}