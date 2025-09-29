<?php

namespace App\Http\Controllers;

use App\Core\Services\CardService;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardCollection; // <-- Importación
use App\Http\Resources\CardResource;   // <-- Importación
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    // GET /api/cards
    public function index(): CardCollection // Cambiamos el tipo de retorno
    {
        $cards = $this->cardService->getCards();
        return new CardCollection($cards); // Usamos la Colección
    }

    // POST /api/cards
    public function store(StoreCardRequest $request): CardResource // Cambiamos el tipo de retorno
    {
        $newCard = $this->cardService->createCard($request->toEntity()); 
        // Retornar la Resource y Laravel maneja el código 201
        return new CardResource($newCard); 
    }

    // GET /api/cards/{card_id}
    public function show(int $id): JsonResponse|CardResource // Cambiamos el tipo de retorno
    {
        $card = $this->cardService->getCard($id);

        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        return new CardResource($card); // Usamos el Resource individual
    }
    
    // GET /api/cards/uuid/{uuid}
    public function showByUuid(string $uuid): JsonResponse|CardResource // Cambiamos el tipo de retorno
    {
        $card = $this->cardService->getCardByUuid($uuid);

        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }
        
        return new CardResource($card); // Usamos el Resource individual
    }

    // PUT/PATCH /api/cards/{card_id}
    public function update(UpdateCardRequest $request, int $id): JsonResponse|CardResource
    {
        try {
            $updatedCard = $this->cardService->updateCard($id, $request->toEntity());
            return new CardResource($updatedCard);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Card not found'], 404);
        }
    }

    // DELETE /api/cards/{card_id}
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->cardService->deleteCard($id);

        if (!$deleted) {
            return response()->json(['message' => 'Card not found or could not be deleted'], 404);
        }

        return response()->json(null, 204);
    }
}