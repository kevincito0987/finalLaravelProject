<?php

namespace App\Policies;

use App\Core\Entities\Evaluation\EvaluationEntity;
use App\Models\User;

/**
 * Define las reglas de autorización para gestionar las Evaluaciones.
 */
class EvaluationPolicy
{
    /**
     * Helper para verificar si el usuario tiene rol de editor (Admin o Therapist).
     * @param User $user
     * @return bool
     */
    protected function isEditor(User $user): bool
    {
        // Asumiendo que el modelo User tiene un método hasRole()
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede listar o ver la colección de Evaluaciones.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles pueden ver (listar) las evaluaciones.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede ver una Evaluación específica.
     *
     * @param User $user
     * @param EvaluationEntity $evaluationEntity
     * @return bool
     */
    public function view(User $user, EvaluationEntity $evaluationEntity): bool
    {
        // Todos los roles pueden ver una evaluación específica.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede crear una nueva Evaluación.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear evaluaciones.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede actualizar una Evaluación.
     *
     * @param User $user
     * @param EvaluationEntity $evaluationEntity
     * @return bool
     */
    public function update(User $user, EvaluationEntity $evaluationEntity): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar evaluaciones.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar una Evaluación.
     *
     * @param User $user
     * @param EvaluationEntity $evaluationEntity
     * @return bool
     */
    public function delete(User $user, EvaluationEntity $evaluationEntity): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar evaluaciones.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede restaurar una Evaluación (si aplicara soft-deletes).
     *
     * @param User $user
     * @param EvaluationEntity $evaluationEntity
     * @return bool
     */
    public function restore(User $user, EvaluationEntity $evaluationEntity): bool
    {
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar permanentemente una Evaluación.
     *
     * @param User $user
     * @param EvaluationEntity $evaluationEntity
     * @return bool
     */
    public function forceDelete(User $user, EvaluationEntity $evaluationEntity): bool
    {
        // Generalmente, solo 'admin' puede forzar la eliminación.
        return $user->hasRole('admin');
    }
}
