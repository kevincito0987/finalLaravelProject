<?php

namespace App\Core\Entities\User;

/**
 * Entidad de Dominio Pura (POPO).
 * Contiene los datos del usuario y es agnóstica a la base de datos (Eloquent).
 */
class UserEntity
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly array $roles = [],
        public readonly ?string $profileImagePath = null
    ) {}
}