<?php

namespace App\Policies;

use App\Models\CommunicationMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommunicationMethodPolicy
{
    /**
     * Determina si el usuario puede realizar cualquier acción (supervisión).
     * Usamos el método 'before' para la verificación rápida de roles.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Si el usuario es 'admin', permitimos todas las acciones
        if ($user->hasRole('admin')) {
            return true;
        }

        return null; // Continuar con la verificación de métodos específicos
    }

    /**
     * Determina si el usuario puede ver la lista de modelos.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        // Solo los administradores pueden ver la lista completa (aunque otros roles podrían necesitar verla)
        // Dado que 'before' ya permite a los administradores, esta línea es redundante si se usa 'before'.
        return $user->hasRole('admin');
    }
    
    // NOTA: Para este caso, con 'before', las otras funciones (view, create, update, delete) son redundantes
    // ya que la verificación de rol de administrador cubre todas las acciones CRUD.
}
