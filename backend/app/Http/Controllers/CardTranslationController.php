<?php

namespace App\Http\Controllers;

use App\Core\Entities\CardTranslationEntity;
use App\Core\Repositories\SupabaseMediaStorage;
use App\Core\Services\CardTranslationService;
use App\Core\Services\MediaUploader;
use App\Http\Requests\StoreCardTranslationRequest;
use App\Http\Requests\UpdateCardTranslationRequest;
use App\Http\Resources\CardTranslationResource;
use App\Models\CardTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CardTranslationController extends Controller
{
    protected CardTranslationService $cardTranslationService;
    protected MediaUploader $mediaUploader;

    public function __construct(
        CardTranslationService $cardTranslationService,
        MediaUploader $mediaUploader
    ) {
        // Corregido: Usamos la instancia inyectada directamente.
        $this->cardTranslationService = $cardTranslationService;
        $this->mediaUploader = $mediaUploader;
    }

    /**
     * Muestra una lista de todas las traducciones.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', CardTranslation::class);
        
        $translations = $this->cardTranslationService->getAllTranslations();
        
        return CardTranslationResource::collection($translations)->response();
    }

    /**
     * Crea una nueva traducción.
     */
    public function store(StoreCardTranslationRequest $request): JsonResponse
    {
        Gate::authorize('create', CardTranslation::class);
        
        $audioPath = null;
        
        // --- 1. Lógica de Subida de Archivo de Audio (Supabase) ---
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $mime = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
            $path = "{$userFolder}/audio/" . uniqid('translation_') . '.' . $extension;
            $content = $file->get();

            $this->mediaUploader->upload($path, $content, $mime);
            $audioPath = $path; 
        }
        // -----------------------------------------------------------

        // Mapeamos los datos validados del Request a la Entidad
        $entity = new CardTranslationEntity(
            null, 
            $request->validated('card_id_translation'), 
            $request->validated('language_code'), 
            $request->validated('key_phrase'), 
            $audioPath, 
        );

        // Si $audioPath es null (no subió archivo), $generateAudio debe ser TRUE.
        $generateAudio = $audioPath === null;

        $newTranslation = $this->cardTranslationService->createTranslation($entity, $generateAudio);

        return (new CardTranslationResource($newTranslation))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Muestra una traducción específica por ID.
     */
    public function show(int $id): JsonResponse
    {
        // Usamos 'viewAny' porque solo pasamos la clase, y esto ya verifica el permiso general.
        Gate::authorize('viewAny', CardTranslation::class); 

        $translation = $this->cardTranslationService->getTranslation($id);

        if (!$translation) {
            return response()->json(['message' => 'Traducción no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

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
        
        // Usar la autorización sobre la clase es más seguro ya que la Entidad no es el Modelo
        // y la lógica de la política solo depende del rol. Usamos 'create' por la misma lógica de roles.
        Gate::authorize('create', CardTranslation::class);
        
        $audioPath = $existingTranslation->audioPath; // Por defecto, mantener la ruta existente

        // --- Lógica de Subida de Archivo de Audio (Actualización) ---
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $mime = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            // Construir la ruta de Supabase (usamos un nuevo nombre)
            $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
            $path = "{$userFolder}/audio/" . uniqid('translation_update_') . '.' . $extension;
            $content = $file->get();

            // Subir el nuevo archivo
            $this->mediaUploader->upload($path, $content, $mime);
            $audioPath = $path; // Guardamos el nuevo path.
        }
        // -----------------------------------------------------------------


        // Mapeamos los campos del request a la entidad
        $updatedEntity = new CardTranslationEntity(
            $existingTranslation->cardTranslationId,
            $existingTranslation->cardId,
            $request->validated('language_code', $existingTranslation->languageCode), 
            $request->validated('key_phrase', $existingTranslation->keyPhrase), 
            $audioPath, 
        );

        $translation = $this->cardTranslationService->updateTranslation($id, $updatedEntity);

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

        // CORRECCIÓN CRÍTICA: Cambiamos a 'create' (o 'viewAny') que solo espera la CLASE.
        // La política 'create' (o 'viewAny') en la política CardTranslationPolicy 
        // solo chequea el rol, lo cual es lo que necesitamos.
        Gate::authorize('create', CardTranslation::class);

        $deleted = $this->cardTranslationService->deleteTranslation($id);

        if ($deleted) {
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'No se pudo eliminar la traducción'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
