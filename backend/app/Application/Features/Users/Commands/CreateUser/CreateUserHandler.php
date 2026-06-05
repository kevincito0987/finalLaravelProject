<?php

namespace App\Application\Features\Users\Commands\CreateUser;

use App\Domain\RepositoriesInterfaces\IUserRepository;
use App\Domain\Entities\User as UserEntity;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use InvalidArgumentException;

class CreateUserHandler
{
    // 🔑 Inyectamos el contrato puro del repositorio
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    /**
     * Procesa el comando para registrar al usuario en el sistema.
     */
    public function handle(CreateUserCommand $command): void
    {
        // 1. Validar si el correo ya existe en el sistema (Regla de negocio crítica)
        $emailVO = new Email($command->email);
        $userExists = $this->userRepository->findByEmail($emailVO);

        if ($userExists !== null) {
            throw new InvalidArgumentException("El correo electrónico ya se encuentra registrado.");
        }

        // 2. Instanciar la contraseña (se autovalida y encripta dentro del Value Object)
        $passwordVO = new Password($command->password);

        // 3. Crear la Entidad de Dominio con los datos que vinieron dentro del $command
        $newUser = new UserEntity(
            id: null, // Es null porque es un registro nuevo
            name: $command->name,
            email: $emailVO,
            password: $passwordVO,
            profilePhotoPath: $command->profilePhotoPath,
            roleId: $command->roleId
        );

        // 4. 🚀 Persistir el nuevo usuario usando el método especializado de creación
        $this->userRepository->create($newUser);
    }
}