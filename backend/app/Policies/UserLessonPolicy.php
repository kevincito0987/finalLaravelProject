<?php

namespace App\Policies;

use App\Core\Entities\User\UserLessonEntity;
use App\Models\User;

/**
 * Define las reglas de autorización para gestionar el progreso de las lecciones de un usuario (UserLessonEntity).
 */
class UserLessonPolicy
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
     * Determina si el usuario puede listar (ver el índice) el progreso de lecciones.
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles ('user', 'admin', 'therapist') pueden ver (GET) el progreso.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede ver un registro específico de UserLesson.
     */
    public function view(User $user, UserLessonEntity $userLesson): bool
    {
        // Todos los roles pueden ver (GET) un registro específico.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede crear un nuevo registro de UserLesson
     * (e.g., marcar una lección como completada).
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear/modificar el estado de completado.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede actualizar un registro de UserLesson.
     */
    public function update(User $user, UserLessonEntity $userLesson): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar un registro de UserLesson.
     */
    public function delete(User $user, UserLessonEntity $userLesson): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar.
        return $this->isEditor($user);
    }
}
