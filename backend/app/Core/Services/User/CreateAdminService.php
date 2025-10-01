<?php 

namespace App\Core\Services\User;

use App\Core\Entities\User\UserEntity;
use App\Core\Interfaces\UserRepositoryInterface;

class CreateAdminService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $name, string $email, string $password, string $roleName): UserEntity
    {
        $userEntity = new UserEntity(
            id: null,
            name: $name,
            email: $email,
            password: $password
        );

        // La lógica específica para este servicio es asignar el rol pasado.
        $savedUser = $this->userRepository->save($userEntity, $roleName);

        // Podrías orquestar el envío de correo aquí también.
        
        return $savedUser;
    }
}