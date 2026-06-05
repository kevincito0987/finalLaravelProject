<?php

namespace App\Domain\RepositoriesInterfaces;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;

interface IUserRepository
{
    public function findById(int $id): ?User;

    public function findByEmail(Email $email): ?User;

    /**
     * Registra un nuevo usuario en el sistema.
     */
    public function create(User $user): User;

    /**
     * Actualiza los datos de un usuario existente.
     */
    public function update(User $user): User;

    public function delete(int $id): bool;
}