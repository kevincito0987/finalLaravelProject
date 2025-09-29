<?php
namespace App\Core\Repositories;

use App\Core\Entities\CategoryEntity;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function getAll(): Collection;
    public function find(int $id): ?CategoryEntity;
    public function create(CategoryEntity $category): CategoryEntity;
    public function update(int $id, CategoryEntity $category): CategoryEntity;
    public function delete(int $id): bool;
}