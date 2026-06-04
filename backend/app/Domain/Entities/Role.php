<?php

namespace App\Domain\Entities;

class Role
{
    private ?int $id;
    private string $name;

    public function __construct(?int $id, string $name)
    {
        $this->id = $id;
        $this->setName($name); // Usamos el mutador para forzar reglas de validación de negocio
    }

    // --- GETTERS ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    // --- MUTADORES CON REGLAS DE DOMINIO ---
    public function setName(string $name): void
    {
        $cleanName = trim($name);
        
        if (empty($cleanName)) {
            throw new \InvalidArgumentException("El nombre del rol no puede estar vacío.");
        }

        // Regla de negocio: Guardar siempre los roles en minúscula para estandarizar (ej: 'admin', 'user')
        $this->name = strtolower($cleanName);
    }
}