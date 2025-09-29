<?php

namespace App\Core\Interfaces;

use App\Core\Entities\CategoryEntity;
use App\Core\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::all()->map(function ($model) {
            return new CategoryEntity($model->category_name, $model->category_id);
        });
    }

    public function find(int $id): ?CategoryEntity
    {
        $model = Category::find($id);
        if (!$model) {
            return null;
        }
        return new CategoryEntity($model->category_name, $model->category_id);
    }

    public function create(CategoryEntity $category): CategoryEntity
    {
        $model = Category::create([
            'category_name' => $category->name
        ]);
        return new CategoryEntity($model->category_name, $model->category_id);
    }

    public function update(int $id, CategoryEntity $category): CategoryEntity
    {
        $model = Category::find($id);
        if (!$model) {
            throw new ModelNotFoundException("Category with ID {$id} not found.");
        }
        
        $model->category_name = $category->name;
        $model->save();

        return new CategoryEntity($model->category_name, $model->category_id);
    }

    public function delete(int $id): bool
    {
        $model = Category::find($id);
        if (!$model) {
            return false;
        }
        return (bool) $model->delete();
    }
}