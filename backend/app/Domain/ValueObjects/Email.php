<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $cleanValue = trim($value);

        if (!filter_var($cleanValue, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El formato del correo electrónico '{$value}' no es válido.");
        }

        $this->value = $cleanValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Permite comparar si dos objetos Email son iguales.
     */
    public function equals(Email $other): bool
    {
        return strtolower($this->value) === strtolower($other->getValue());
    }

    /**
     * Método mágico para cuando se use el objeto directamente como un string.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}