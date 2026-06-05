<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Password
{
    private string $hash;

    public function __construct(string $value, bool $isAlreadyHashed = false)
    {
        if (empty($value)) {
            throw new InvalidArgumentException("La contraseña no puede estar vacía.");
        }

        if ($isAlreadyHashed) {
            $this->hash = $value;
        } else {
            // Regla de negocio OWASP básica: Mínimo 8 caracteres
            if (strlen($value) < 8) {
                throw new InvalidArgumentException("La contraseña debe tener al menos 8 caracteres.");
            }
            // Encriptamos usando el algoritmo nativo seguro de PHP
            $this->hash = password_hash($value, PASSWORD_BCRYPT);
        }
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Verifica de forma segura si un texto plano coincide con el hash guardado.
     */
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hash);
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}