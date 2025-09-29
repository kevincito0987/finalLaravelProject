<?php
namespace App\Policies;

use App\Models\Card;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardPolicy
{
    /**
     * Permite que los administradores eviten cualquier verificación de política.
     */
    public function before(User $user, string $ability): ?bool
    {
        // EL ADMIN TIENE ACCESO TOTAL (CRUD completo)
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }
    
    /**
     * Determina si el usuario puede ver la lista de tarjetas (index).
     * (Cualquier usuario autenticado puede leer, es decir: user, therapist, admin)
     */
    public function viewAny(User $user): bool
    {
        // Permitimos el acceso si es 'user' O 'therapist'. 'admin' ya se maneja en before().
        return $user->hasRole('user') || $user->hasRole('therapist'); 
    }

    /**
     * Determina si el usuario puede ver una tarjeta específica (show, showByUuid).
     */
    public function view(User $user, Card $card): bool
    {
        // Permitimos el acceso si es 'user' O 'therapist'.
        return $user->hasRole('user') || $user->hasRole('therapist');
    }

    // -- Roles de Escritura (Solo therapist y admin) --

    /**
     * Determina si el usuario puede crear tarjetas (POST).
     */
    public function create(User $user): bool
    {
        // Solo therapist puede crear. (admin lo hace por before())
        return $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede actualizar la tarjeta (PUT/PATCH).
     */
    public function update(User $user, Card $card): bool
    {
        // Solo therapist puede actualizar.
        return $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede eliminar la tarjeta (DELETE).
     */
    public function delete(User $user, Card $card): bool
    {
        // Solo therapist puede eliminar.
        return $user->hasRole('therapist');
    }
}