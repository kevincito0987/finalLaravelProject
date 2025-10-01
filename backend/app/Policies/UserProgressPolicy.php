<?php

namespace App\Policies;

use App\Core\Entities\User\UserProgressEntity;
use App\Models\User;

/**
 * Define las reglas de autorización para gestionar el progreso general de un usuario (UserProgressEntity).
 */
class UserProgressPolicy
{
    /**
     * Helper para verificar si el usuario tiene rol de editor (Admin o Therapist).
     * @param User $user
     * @return bool
     */
    protected function isEditor(User $user): bool
    {
        // Asume que el modelo User tiene un método hasRole() que verifica si el usuario
        // tiene el rol de 'admin' o 'therapist'.
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede listar (ver el índice) el progreso de usuarios.
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles ('user', 'admin', 'therapist') pueden ver (GET) el progreso.
        // Nota: En una app real, el rol 'user' solo debería ver su propio progreso, 
        // pero esta política permite ver la lista si el backend filtra por usuario actual.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede ver un registro específico de UserProgress.
     */
    public function view(User $user, UserProgressEntity $userProgress): bool
    {
        // Todos los roles pueden ver (GET) un registro específico.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede crear un nuevo registro de UserProgress
     * (e.g., iniciar el seguimiento del progreso para un nuevo usuario).
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear/iniciar registros de progreso.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede actualizar un registro de UserProgress
     * (e.g., modificar estadísticas o logros).
     */
    public function update(User $user, UserProgressEntity $userProgress): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar el progreso.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar un registro de UserProgress.
     */
    public function delete(User $user, UserProgressEntity $userProgress): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar registros de progreso.
        return $this->isEditor($user);
    }
}
