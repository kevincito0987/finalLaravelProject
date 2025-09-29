<?php
namespace App\Core\Services;

use App\Core\Entities\CategoryEntity;
use App\Core\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function getCategory(int $id): ?CategoryEntity
    {
        // Puedes agregar lógica de negocio o validación aquí
        return $this->categoryRepository->find($id);
    }
    
    public function createCategory(CategoryEntity $category): CategoryEntity
    {
        // Lógica de negocio antes de la creación
        return $this->categoryRepository->create($category);
    }

    public function updateCategory(int $id, CategoryEntity $category): CategoryEntity
    {
        // Lógica de negocio antes de la actualización
        return $this->categoryRepository->update($id, $category);
    }

    public function deleteCategory(int $id): bool
    {
        // Lógica de negocio antes de la eliminación
        return $this->categoryRepository->delete($id);
    }
}