<?php

namespace App\Core\Repositories;

use App\Core\Entities\UserEntity;
use App\Core\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity
    {
        $userModel = User::where('email', $email)->first();

        if (!$userModel) {
            return null;
        }

        return new UserEntity(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            password: $userModel->password, // Es el hash
            roles: $userModel->roles->pluck('name')->toArray(),
            profileImagePath: $userModel->profile_image_path,
        );
    }

    public function save(UserEntity $user, string $roleName = 'user'): UserEntity
    {
        // 1. Crear el usuario en la DB
        $userModel = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->password),
            'profile_image_path' => $user->profileImagePath,
        ]);

        // 2. Asignar el rol
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $userModel->roles()->syncWithoutDetaching([$role->id]);
        }

        // 3. Retornar la Entidad (actualizada con ID y roles)
        return new UserEntity(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            password: $userModel->password,
            roles: $userModel->roles->pluck('name')->toArray(),
            profileImagePath: $userModel->profile_image_path,
        );
    }
}