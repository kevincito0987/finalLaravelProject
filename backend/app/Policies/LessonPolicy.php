<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lesson; // Asegúrate de importar el modelo Lesson

class LessonPolicy
{
    /**
     * Define si cualquier usuario puede realizar acciones, o si el usuario
     * tiene permisos para listar/ver todas las lecciones.
     * * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles (incluido 'user', 'admin' y 'therapist') pueden ver la lista.
        return $user->hasRole('user') || $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Define si el usuario puede ver una lección específica.
     * * @param \App\Models\User $user
     * @param \App\Models\Lesson $lesson
     * @return bool
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // Todos los roles pueden ver una lección específica.
        return $user->hasRole('user') || $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Define si el usuario puede crear lecciones.
     * * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear.
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Define si el usuario puede actualizar la lección dada.
     * * @param \App\Models\User $user
     * @param \App\Models\Lesson $lesson
     * @return bool
     */
    public function update(User $user, Lesson $lesson): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar.
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Define si el usuario puede eliminar la lección.
     * * @param \App\Models\User $user
     * @param \App\Models\Lesson $lesson
     * @return bool
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar.
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    // Opcional: El método restore es para soft-deletes
    public function restore(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin'); 
    }

    // Opcional: El método forceDelete elimina permanentemente
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin'); 
    }
}
