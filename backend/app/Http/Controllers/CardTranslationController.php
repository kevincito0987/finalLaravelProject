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

/**
 * @OA\Tag(
 * name="Card Translations",
 * description="Operaciones relacionadas con las traducciones de las tarjetas (TTS, audio files)."
 * )
 */
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
     * @OA\Get(
     * path="/api/card-translations",
     * operationId="getCardTranslationsIndex",
     * tags={"Card Translations"},
     * summary="Muestra una lista de todas las traducciones de tarjetas.",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Lista de traducciones obtenida exitosamente.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/CardTranslationResource")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acción no autorizada.")
     * )
     * Muestra una lista de todas las traducciones.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', CardTranslation::class);
        
        $translations = $this->cardTranslationService->getAllTranslations();
        
        // Asumiendo que CardTranslationResource::collection retorna JsonResponse
        return CardTranslationResource::collection($translations)->response();
    }

    /**
     * @OA\Post(
     * path="/api/card-translations",
     * operationId="createCardTranslation",
     * tags={"Card Translations"},
     * summary="Crea una nueva traducción para una tarjeta. Genera audio TTS si 'audio_file' no se proporciona.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"card_id_translation", "language_code", "key_phrase"},
     * @OA\Property(property="card_id_translation", type="integer", description="ID de la tarjeta a traducir.", example=10),
     * @OA\Property(property="language_code", type="string", description="Código del idioma (e.g., es, en).", example="en"),
     * @OA\Property(property="key_phrase", type="string", description="Frase clave a traducir.", example="Hello, world"),
     * @OA\Property(property="audio_file", type="string", format="binary", nullable=true, description="Archivo de audio MP3 opcional. Si no se provee, se usa TTS.")
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Traducción creada y audio generado (o subido) exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardTranslationResource")
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acción no autorizada (solo terapeutas/administradores)."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
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
     * @OA\Get(
     * path="/api/card-translations/{id}",
     * operationId="getCardTranslationById",
     * tags={"Card Translations"},
     * summary="Muestra una traducción específica por ID.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la traducción."
     * ),
     * @OA\Response(
     * response=200,
     * description="Traducción obtenida exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardTranslationResource")
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acción no autorizada."),
     * @OA\Response(response=404, description="Traducción no encontrada.")
     * )
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
     * @OA\Post(
     * path="/api/card-translations/{id}",
     * operationId="updateCardTranslation",
     * tags={"Card Translations"},
     * summary="Actualiza una traducción existente por ID.",
     * description="Se debe usar POST con campo _method=PUT/PATCH en multipart/form-data. Genera audio TTS si 'audio_file' no se proporciona y se cambia la 'key_phrase'.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la traducción a actualizar."
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(property="_method", type="string", enum={"PUT", "PATCH"}, example="PATCH", description="Método HTTP para simular PUT/PATCH."),
     * @OA\Property(property="language_code", type="string", nullable=true, description="Código del idioma (e.g., es, en).", example="fr"),
     * @OA\Property(property="key_phrase", type="string", nullable=true, description="Nueva frase clave.", example="Bonjour le monde"),
     * @OA\Property(property="audio_file", type="string", format="binary", nullable=true, description="Nuevo archivo de audio MP3 opcional. Si no se provee, se usa el audio existente o se genera TTS si la frase cambia.")
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Traducción actualizada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/CardTranslationResource")
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acción no autorizada."),
     * @OA\Response(response=404, description="Traducción no encontrada."),
     * @OA\Response(response=422, description="Error de validación.")
     * )
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
     * @OA\Delete(
     * path="/api/card-translations/{id}",
     * operationId="deleteCardTranslation",
     * tags={"Card Translations"},
     * summary="Elimina una traducción específica por ID.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la traducción a eliminar."
     * ),
     * @OA\Response(response=204, description="Traducción eliminada exitosamente (No Content)."),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acción no autorizada."),
     * @OA\Response(response=404, description="Traducción no encontrada."),
     * @OA\Response(response=500, description="Error interno del servidor al intentar eliminar.")
     * )
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
