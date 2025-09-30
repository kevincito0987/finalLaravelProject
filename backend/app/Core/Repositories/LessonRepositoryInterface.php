<?php

namespace App\Core\Repositories;

use App\Core\Entities\LessonEntity;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Contrato para el Repositorio de Lecciones.
 * Define los métodos CRUD que cualquier implementación de repositorio debe cumplir.
 */
interface LessonRepositoryInterface
{
    /**
     * Obtiene una colección paginada de todas las lecciones.
     *
     * @param int $perPage Número de elementos por página.
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Busca una lección por su ID.
     *
     * @param int $id El ID de la lección.
     * @return LessonEntity|null Retorna la entidad de lección o null si no se encuentra.
     */
    public function findById(int $id): ?LessonEntity;

    /**
     * Crea una nueva lección en el almacenamiento.
     *
     * @param LessonEntity $lessonEntity La entidad de lección a crear.
     * @return LessonEntity La entidad creada con su ID asignado.
     */
    public function create(LessonEntity $lessonEntity): LessonEntity;

    /**
     * Actualiza una lección existente en el almacenamiento.
     *
     * @param int $id El ID de la lección a actualizar.
     * @param LessonEntity $lessonEntity La entidad con los datos actualizados.
     * @return LessonEntity La entidad actualizada.
     * @throws \Exception Si la lección no existe.
     */
    public function update(int $id, LessonEntity $lessonEntity): LessonEntity;

    /**
     * Elimina una lección del almacenamiento.
     *
     * @param int $id El ID de la lección a eliminar.
     * @return bool True si la eliminación fue exitosa.
     */
    public function delete(int $id): bool;
}
