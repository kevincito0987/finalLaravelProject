<?php

namespace App\Policies;

use App\Models\CardTranslation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardTranslationPolicy
{
    /**
     * Determina si el usuario puede ver cualquier traducción.
     * Permitido para todos los usuarios autenticados.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden listar las traducciones
        return true;
    }

    /**
     * Determina si el usuario puede ver una traducción específica.
     * Permitido para todos los usuarios autenticados.
     */
    public function view(User $user, CardTranslation $cardTranslation): bool
    {
        // Todos los usuarios autenticados pueden ver una traducción específica
        return true;
    }

    /**
     * Determina si el usuario puede crear traducciones.
     * Permitido solo para roles 'therapist' o 'admin'.
     */
    public function create(User $user): bool
    {
        // Solo terapeutas o administradores pueden crear
        return $user->hasRole('therapist') || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede actualizar la traducción.
     * Permitido solo para roles 'therapist' o 'admin'.
     * También podríamos añadir lógica si solo el creador puede editar, pero por ahora se basa en el rol.
     */
    public function update(User $user, CardTranslation $cardTranslation): bool
    {
        // Solo terapeutas o administradores pueden actualizar
        return $user->hasRole('therapist') || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede eliminar la traducción.
     * Permitido solo para roles 'therapist' o 'admin'.
     */
    public function delete(User $user, CardTranslation $cardTranslation): bool
    {
        // Solo terapeutas o administradores pueden eliminar
        return $user->hasRole('therapist') || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede restaurar la traducción (no aplica, pero se incluye por convención).
     */
    public function restore(User $user, CardTranslation $cardTranslation): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente la traducción (no aplica, pero se incluye por convención).
     */
    public function forceDelete(User $user, CardTranslation $cardTranslation): bool
    {
        return $user->hasRole('admin');
    }
}
