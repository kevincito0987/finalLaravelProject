<?php

namespace App\Http\Controllers;

use App\Core\Entities\CardTranslationEntity;
use App\Core\Services\CardTranslationService;
use App\Http\Requests\StoreCardTranslationRequest;
use App\Http\Requests\UpdateCardTranslationRequest;
use App\Http\Resources\CardTranslationResource;
use App\Models\CardTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CardTranslationController extends Controller
{
    protected CardTranslationService $cardTranslationService;

    public function __construct(CardTranslationService $cardTranslationService)
    {
        
        $this->cardTranslationService = $cardTranslationService;
    }

    /**
     * Muestra una lista de todas las traducciones.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', CardTranslation::class);
        
        $translations = $this->cardTranslationService->getAllTranslations();
        
        // CORRECCIÓN: Usamos ->response() para asegurar que se devuelve un JsonResponse
        return CardTranslationResource::collection($translations)->response();
    }

    /**
     * Crea una nueva traducción.
     */
    public function store(StoreCardTranslationRequest $request): JsonResponse
    {
        Gate::authorize('create', CardTranslation::class);

        // Mapeamos los datos validados del Request a la Entidad
        $entity = new CardTranslationEntity(
            cardTranslationId: null, // Será asignado por la BD
            cardIdTranslation: $request->validated('card_id_translation'),
            languageCode: $request->validated('language_code'),
            keyPhrase: $request->validated('key_phrase'),
            audioPath: $request->validated('audio_path'),
        );

        $newTranslation = $this->cardTranslationService->createTranslation($entity);

        return (new CardTranslationResource($newTranslation))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Muestra una traducción específica por ID.
     */
    public function show(int $id): JsonResponse
    {
        $translation = $this->cardTranslationService->getTranslation($id);

        if (!$translation) {
            return response()->json(['message' => 'Traducción no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        Gate::authorize('view', $translation); 

        // CORRECCIÓN: Usamos ->response() para asegurar que se devuelve un JsonResponse
        return (new CardTranslationResource($translation))->response();
    }

    /**
     * Actualiza una traducción existente.
     */
    public function update(UpdateCardTranslationRequest $request, int $id): JsonResponse
    {
        $existingTranslation = $this->cardTranslationService->getTranslation($id);

        if (!$existingTranslation) {
            return response()->json(['message' => 'Traducción no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $existingTranslation);

        // Mapeamos los campos del request a la entidad, usando los valores existentes si no se proporcionan
        $updatedEntity = new CardTranslationEntity(
            cardTranslationId: $existingTranslation->cardTranslationId,
            cardIdTranslation: $request->validated('card_id_translation', $existingTranslation->cardIdTranslation), // No debería cambiar
            languageCode: $request->validated('language_code', $existingTranslation->languageCode),
            keyPhrase: $request->validated('key_phrase', $existingTranslation->keyPhrase),
            audioPath: $request->validated('audio_path', $existingTranslation->audioPath),
        );

        $translation = $this->cardTranslationService->updateTranslation($id, $updatedEntity);

        // CORRECCIÓN: Usamos ->response() para asegurar que se devuelve un JsonResponse
        return (new CardTranslationResource($translation))->response();
    }

    /**
     * Elimina una traducción.
     */
    public function destroy(int $id): JsonResponse
    {
        $translation = $this->cardTranslationService->getTranslation($id);

        if (!$translation) {
            return response()->json(['message' => 'Traducción no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        Gate::authorize('delete', $translation);

        $deleted = $this->cardTranslationService->deleteTranslation($id);

        if ($deleted) {
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'No se pudo eliminar la traducción'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
