<?php

namespace App\Core\Interfaces;

use App\Core\Entities\User\UserEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity;
    public function save(UserEntity $user, string $roleName = 'user'): UserEntity;
}
