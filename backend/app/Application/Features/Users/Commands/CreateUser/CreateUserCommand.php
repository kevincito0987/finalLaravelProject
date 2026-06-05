<?php

namespace App\Application\Features\Users\Commands\CreateUser;

class CreateUserCommand
{
    /**
     * El Command solo transporta datos limpios hacia el Handler.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly int $roleId,
        public readonly ?string $profilePhotoPath = null
    ) {}
}