<?php

namespace App\Policies;

use App\Models\User;
use App\Core\Entities\EvaluationQuestion; // Importamos la Entidad de Dominio

/**
 * Define las reglas de autorización para gestionar las Preguntas de Evaluación.
 */
class EvaluationQuestionPolicy

{
    /**
     * Helper para verificar si el usuario tiene rol de editor (Admin o Therapist).
     * @param User $user
     * @return bool
     */

     
    protected function isEditor(User $user): bool
    {
        // Asume que el modelo User tiene un método hasRole() o que el rol está disponible
        // directamente, pero usar hasRole() es más idiomático en Laravel.
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede listar (ver el índice) las preguntas.
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles pueden ver las preguntas.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede ver una pregunta específica.
     */
    public function view(User $user, EvaluationQuestion $evaluationQuestion): bool
    {
        // Todos los roles pueden ver una pregunta específica.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede crear una nueva pregunta.
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear preguntas.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede actualizar una pregunta.
     */
    public function update(User $user, EvaluationQuestion $evaluationQuestion): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar una pregunta.
     */
    public function delete(User $user, EvaluationQuestion $evaluationQuestion): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar.
        return $this->isEditor($user);
    }
}
