<?php

namespace App\Http\Controllers;

use App\Core\Services\CategoryService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Endpoints para la gestión de categorías"
 * )
 */
class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Listar todas las categorías",
     *     description="Devuelve una lista de todas las categorías disponibles. Acceso: user, admin, therapist.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías obtenida con éxito.",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Educación")
     *         ))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getCategories();
        return response()->json($categories);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Crear una nueva categoría",
     *     description="Crea una nueva categoría. Acceso: admin, therapist.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Salud")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Salud")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $newCategory = $this->categoryService->createCategory($request->toEntity()); 
        return response()->json($newCategory, 201);
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Obtener una categoría por ID",
     *     description="Devuelve la información de una categoría específica. Acceso: user, admin, therapist.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría obtenida con éxito.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Educación")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Categoría no encontrada.")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategory($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Actualizar una categoría",
     *     description="Actualiza el nombre de una categoría existente. Acceso: admin, therapist.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Cultura")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Cultura")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Categoría no encontrada."),
     *     @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $updatedCategory = $this->categoryService->updateCategory($id, $request->toEntity());
            return response()->json($updatedCategory);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Eliminar una categoría",
     *     description="Elimina una categoría por su ID. Acceso: admin, therapist.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=204, description="Categoría eliminada con éxito."),
     *     @OA\Response(response=404, description="Categoría no encontrada.")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->categoryService->deleteCategory($id);

        if (!$deleted) {
            return response()->json(['message' => 'Category not found or could not be deleted'], 404);
        }

        return response()->json(null, 204);
    }
}
