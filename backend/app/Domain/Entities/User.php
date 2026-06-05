<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class User
{
    public function __construct(
        private ?int $id,
        private string $name,
        private Email $email,       // 🔑 Tipado con el Value Object global
        private Password $password, // 🔑 Tipado con el Value Object global
        private ?string $profilePhotoPath,
        private int $roleId,
        private ?Role $role = null, 
        private ?string $mfaSecret = null,
        private bool $isMfaEnabled = false
    ) {}

    // Getters actualizados
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getPassword(): Password { return $this->password; }
    public function getProfilePhotoPath(): ?string { return $this->profilePhotoPath; }
    public function getRoleId(): int { return $this->roleId; }
    public function getRole(): ?Role { return $this->role; }
    public function getMfaSecret(): ?string { return $this->mfaSecret; }
    public function isMfaEnabled(): bool { return $this->isMfaEnabled; }

    // Mutadores actualizados usando los objetos de valor
    public function changePassword(Password $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function updateEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function enableMfa(string $secret): void
    {
        $this->mfaSecret = $secret;
        $this->isMfaEnabled = true;
    }
}