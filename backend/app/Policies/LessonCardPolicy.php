<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lesson; // Se mantiene la referencia a Lesson, ya que la LessonCard pertenece a una Lesson
use App\Models\LessonCard; // Asumo que este es el modelo que se pasará en 'view'

/**
 * Define las reglas de autorización para gestionar las asociaciones LessonCard
 * (añadir, reordenar y eliminar tarjetas de una lección).
 */
class LessonCardPolicy
{
    /**
     * Helper para verificar si el usuario tiene rol de editor (Admin o Therapist).
     * @param User $user
     * @return bool
     */
    protected function isEditor(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('therapist');
    }

    /**
     * Determina si el usuario puede listar o ver la colección de LessonCard.
     * Dado que LessonCard es una asociación, esto generalmente se traduce
     * en ver las tarjetas de una lección.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Todos los roles pueden ver la lista de tarjetas de una lección.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede ver una asociación LessonCard específica.
     * (Aunque en la práctica, rara vez se ve una asociación individualmente).
     *
     * @param User $user
     * @param LessonCardModel $lessonCard
     * @return bool
     */
    public function view(User $user, LessonCard $lessonCard): bool
    {
        // Todos los roles pueden ver una asociación específica.
        return $user->hasRole('user') || $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede crear una nueva asociación LessonCard.
     * (Es decir, añadir una tarjeta a una lección).
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Solo 'admin' y 'therapist' pueden crear/añadir tarjetas a una lección.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede actualizar una asociación LessonCard.
     * Esto incluye actualizar el orden de la tarjeta ('order_in_lesson').
     *
     * @param User $user
     * @param LessonCardModel $lessonCard
     * @return bool
     */
    public function update(User $user, LessonCard $lessonCard): bool
    {
        // Solo 'admin' y 'therapist' pueden actualizar/reordenar.
        return $this->isEditor($user);
    }

    /**
     * Determina si el usuario puede eliminar una asociación LessonCard.
     * (Es decir, quitar una tarjeta de una lección).
     *
     * @param User $user
     * @param LessonCard $lessonCard
     * @return bool
     */
    public function delete(User $user, LessonCard $lessonCard): bool
    {
        // Solo 'admin' y 'therapist' pueden eliminar/quitar tarjetas.
        return $this->isEditor($user);
    }

    // Opcional: Si se necesitara autorizar un reordenamiento masivo,
    // se podría usar un método adicional o el método 'update' en el controlador.
    // Aquí usamos 'update' ya que reordenar es una actualización de la columna 'order_in_lesson'.
}
