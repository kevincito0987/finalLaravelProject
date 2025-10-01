<?php

namespace App\Core\Services;

use App\Core\Entities\Evaluation\EvaluationEntity;
use App\Core\Interfaces\EvaluationRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Clase de Servicio (Caso de Uso/Interactor) para manejar la lógica de negocio
 * relacionada con las Evaluaciones.
 * Depende de la interfaz del repositorio, no de su implementación concreta.
 */
class EvaluationService
{
    /**
     * @var EvaluationRepositoryInterface
     */
    protected $evaluationRepository;

    /**
     * Inyecta la dependencia del repositorio a través del constructor (Inversión de Dependencias).
     *
     * @param EvaluationRepositoryInterface $evaluationRepository
     */
    public function __construct(EvaluationRepositoryInterface $evaluationRepository)
    {
        $this->evaluationRepository = $evaluationRepository;
    }

    /**
     * Obtiene una Evaluación por el ID de la lección.
     * Aquí podrías añadir lógica de negocio, como verificar permisos.
     *
     * @param int $lessonId
     * @return EvaluationEntity|null
     */
    public function getEvaluationByLessonId(int $lessonId): ?EvaluationEntity
    {
        // Lógica de negocio adicional (ej. registrar acceso, validar estado de la lección)
        return $this->evaluationRepository->findEvaluationByLessonId($lessonId);
    }

    /**
     * Crea una nueva evaluación para una lección.
     *
     * @param int $lessonId
     * @return EvaluationEntity
     * @throws \Exception Si la evaluación ya existe (ejemplo de regla de negocio).
     */
    public function createNewEvaluation(int $lessonId): EvaluationEntity
    {
        // 1. Aplicar la Regla de Negocio: Una lección solo puede tener una evaluación.
        if ($this->evaluationRepository->findEvaluationByLessonId($lessonId)) {
            // En una aplicación real, usarías una Excepción de Dominio (Domain Exception)
            throw new \Exception('La lección con ID ' . $lessonId . ' ya tiene una evaluación asociada.');
        }

        // 2. Crear la Entidad Pura para pasarla al Repositorio.
        $newEvaluationEntity = new EvaluationEntity(
            lesson_id_evaluation: $lessonId
        );

        // 3. Persistir la entidad a través del repositorio.
        return $this->evaluationRepository->createEvaluation($newEvaluationEntity);
    }

    /**
     * Obtiene todas las evaluaciones.
     * @return Collection<EvaluationEntity>
     */
    public function getAllEvaluations(): Collection
    {
        return $this->evaluationRepository->getAllEvaluations();
    }
}
