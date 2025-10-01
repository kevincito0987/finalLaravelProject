<?php

// app/Core/Services/Auth/RegisterUserService.php
namespace App\Core\Services\Auth;

use App\Core\Entities\User\UserEntity;
use App\Core\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail; // Asegúrate de que esta clase exista

class RegisterUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(string $name, string $email, string $password): UserEntity
    {
        $userEntity = new UserEntity(
            id: null,
            name: $name,
            email: $email,
            password: $password
        );

        // La lógica de asignación de rol por defecto ('user') se traslada al Repositorio o
        // podrías pasarla aquí al método save.
        $savedUser = $this->userRepository->save($userEntity, 'user');

        // Lógica de notificación (es un detalle, pero se orquesta desde el servicio)
        // Mail::to($savedUser->email)->queue(new UserRegisteredMail($savedUser)); // Opción 1: Laravel Mailer
        // Podrías abstraer el Mailer como un "NotifierInterface" si quieres ser más estricto
        
        return $savedUser;
    }
}