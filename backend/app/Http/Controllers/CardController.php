<?php

namespace App\Http\Controllers;

use App\Core\Services\CardService;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardCollection;
use App\Http\Resources\CardResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller; // Usamos el alias completo para consistencia
use Illuminate\Support\Facades\Gate;
use App\Models\Card; // Necesario para la autorización

/**
 * @OA\Tag(
 * name="Cards",
 * description="Operaciones CRUD para la gestión de tarjetas (Cards) de comunicación."
 * )
 */
class CardController extends Controller
{
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * @OA\Get(
     * path="/api/cards",
     * tags={"Cards"},
     * summary="Obtener todas las tarjetas (Cards)",
     * description="Devuelve una lista de todas las tarjetas con sus detalles de categoría y método de comunicación.",
     * @OA\Response(
     * response=200,
     * description="Lista de tarjetas obtenida exitosamente.",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CardResource"))
     * )
     * )
     * )
     */
    public function index(): CardCollection
    {
        // Asumiendo que 'viewAny' en CardPolicy permite a todos los autenticados.
        // Si no tienes CardPolicy, Laravel podría intentar usar CardTranslationPolicy por error.
        // Se recomienda crear App\Policies\CardPolicy.php si vas a usar Gate::authorize.
        // Por ahora, lo dejamos sin Gate si no tienes CardPolicy implementada.
        // Gate::authorize('viewAny', Card::class);

        $cards = $this->cardService->getCards();
        return new CardCollection($cards);
    }

    /**
     * @OA\Post(
     * path="/api/cards",
     * tags={"Cards"},
     * summary="Crear una nueva tarjeta",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/StoreCardRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Tarjeta creada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardResource")
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="No autorizado (Requiere rol therapist/admin)."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function store(StoreCardRequest $request): CardResource
    {
        // Asumiendo que el middleware ya chequeó el rol (therapist/admin)
        // Gate::authorize('create', Card::class); 

        $newCard = $this->cardService->createCard($request->toEntity()); 
        return new CardResource($newCard); 
    }

    /**
     * @OA\Get(
     * path="/api/cards/{card_id}",
     * tags={"Cards"},
     * summary="Obtener una tarjeta por ID",
     * description="Devuelve los detalles de una tarjeta específica por su clave primaria.",
     * @OA\Parameter(
     * name="card_id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarjeta obtenida exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardResource")
     * ),
     * @OA\Response(response=404, description="Tarjeta no encontrada.")
     * )
     */
    public function show(int $id): JsonResponse|CardResource
    {
        // Gate::authorize('view', Card::class);

        $card = $this->cardService->getCard($id);

        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        return new CardResource($card);
    }
    
    /**
     * @OA\Get(
     * path="/api/cards/uuid/{uuid}",
     * tags={"Cards"},
     * summary="Obtener una tarjeta por UUID",
     * description="Devuelve los detalles de una tarjeta específica por su UUID único.",
     * @OA\Parameter(
     * name="uuid",
     * in="path",
     * required=true,
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarjeta obtenida exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardResource")
     * ),
     * @OA\Response(response=404, description="Tarjeta no encontrada.")
     * )
     */
    public function showByUuid(string $uuid): JsonResponse|CardResource
    {
        // Gate::authorize('view', Card::class);

        $card = $this->cardService->getCardByUuid($uuid);

        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }
        
        return new CardResource($card);
    }

    /**
     * @OA\Patch(
     * path="/api/cards/{card_id}",
     * tags={"Cards"},
     * summary="Actualizar una tarjeta existente",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="card_id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateCardRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarjeta actualizada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardResource")
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="No autorizado (Requiere rol therapist/admin)."),
     * @OA\Response(response=404, description="Tarjeta no encontrada."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
     */
    public function update(UpdateCardRequest $request, int $id): JsonResponse|CardResource
    {
        // Gate::authorize('update', Card::class); // Autorización a nivel de clase

        try {
            $updatedCard = $this->cardService->updateCard($id, $request->toEntity());
            return new CardResource($updatedCard);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Card not found'], 404);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/cards/{card_id}",
     * tags={"Cards"},
     * summary="Eliminar una tarjeta",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="card_id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=204, description="Tarjeta eliminada exitosamente (No Content)."),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="No autorizado (Requiere rol therapist/admin)."),
     * @OA\Response(response=404, description="Tarjeta no encontrada o no pudo ser eliminada.")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        // Gate::authorize('delete', Card::class); // Autorización a nivel de clase

        $deleted = $this->cardService->deleteCard($id);

        if (!$deleted) {
            return response()->json(['message' => 'Card not found or could not be deleted'], 404);
        }

        return response()->json(null, 204);
    }
}
