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
use Illuminate\Support\Facades\Log;

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
        
        return CardTranslationResource::collection($translations)->response();
    }

    /**
     * @OA\Post(
     * path="/api/card-translations",
     * operationId="createCardTranslation",
     * tags={"Card Translations"},
     * summary="Crea una nueva traducción para una tarjeta. Genera audio TTS si no se proporciona 'audio_file' ni 'audio_url'.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Datos para crear la traducción. 'audio_file' y 'audio_url' son mutuamente excluyentes.",
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"card_id_translation", "language_code", "key_phrase"},
     * @OA\Property(property="card_id_translation", type="integer", description="ID de la tarjeta principal (FK).", example=10),
     * @OA\Property(property="language_code", type="string", description="Código del idioma (e.g., es, en).", example="en"),
     * @OA\Property(property="key_phrase", type="string", description="Frase clave a traducir.", example="Hello, world"),
     * @OA\Property(property="audio_file", type="string", format="binary", nullable=true, description="Archivo de audio MP3 opcional. Prohíbe el uso de 'audio_url'."),
     * @OA\Property(property="audio_url", type="string", format="url", nullable=true, description="URL externa del archivo de audio. Prohíbe el uso de 'audio_file'.")
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Traducción creada y audio generado (o subido/enlazado) exitosamente.",
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
        $generateAudio = true; // Por defecto intentamos generar TTS

        // --- 1. Lógica de Audio: Archivo de Audio Subido ---
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $mime = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
            $path = "{$userFolder}/audio/" . uniqid('translation_') . '.' . $extension;
            $content = $file->get();

            try {
                $this->mediaUploader->upload($path, $content, $mime); 
                $audioPath = $path; 
                $generateAudio = false; // No necesitamos TTS
            } catch (\Exception $e) {
                Log::error("Fallo al subir archivo de audio: " . $e->getMessage());
                // Si falla la subida, volvemos a intentar TTS
                $audioPath = null;
                $generateAudio = true;
            }
        } 
        // --- 2. Lógica de Audio: URL Externa ---
        elseif ($request->has('audio_url')) {
            $audioPath = $request->input('audio_url'); 
            $generateAudio = false;
        }
        // -----------------------------------------------------------

        // Mapeamos los datos validados del Request a la Entidad
        $entity = new CardTranslationEntity(
            null, 
            $request->validated('card_id_translation'), // Aquí usamos el ID del cuerpo de la petición
            $request->validated('language_code'), 
            $request->validated('key_phrase'), 
            $audioPath, // Null si se debe generar, o la ruta/URL
        );

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
     * description="Se debe usar POST con campo _method=PUT/PATCH en multipart/form-data. Si 'key_phrase' cambia y no se proporciona 'audio_file' o 'audio_url', se regenera el audio TTS.",
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
     * @OA\Property(property="audio_file", type="string", format="binary", nullable=true, description="Nuevo archivo de audio MP3 opcional. Prohíbe el uso de 'audio_url'."),
     * @OA\Property(property="audio_url", type="string", format="url", nullable=true, description="Nueva URL externa del archivo de audio. Prohíbe el uso de 'audio_file'.")
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
        
        Gate::authorize('create', CardTranslation::class);
        
        $audioPath = $existingTranslation->audioPath; // Por defecto, mantener la ruta existente
        $shouldRegenerateAudio = false; 
        $audioProvidedInRequest = false;

        // --- Lógica de Subida de Archivo de Audio (Actualización) ---
        if ($request->hasFile('audio_file')) {
            $audioProvidedInRequest = true;
            $file = $request->file('audio_file');
            $mime = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            // 1. Subir el nuevo archivo
            $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
            $path = "{$userFolder}/audio/" . uniqid('translation_update_') . '.' . $extension;
            $content = $file->get();

            try {
                $this->mediaUploader->upload($path, $content, $mime);
                $audioPath = $path; // Guardamos el nuevo path.
            } catch (\Exception $e) {
                Log::error("Fallo al subir archivo de audio en la actualización: " . $e->getMessage());
                // Si falla la subida, mantenemos el path anterior y NO regeneramos/cambiamos nada.
                $audioPath = $existingTranslation->audioPath; 
                $audioProvidedInRequest = false; // Ignoramos el intento de subida
            }
        } 
        // --- Lógica de URL Externa (Actualización) ---
        elseif ($request->has('audio_url')) {
            $audioProvidedInRequest = true;
            $audioPath = $request->input('audio_url'); 
        }

        // Si NO se proporcionó audio (archivo o URL), verificamos si se cambió la key_phrase para TTS.
        if (!$audioProvidedInRequest) {
            $newKeyPhrase = $request->validated('key_phrase', $existingTranslation->keyPhrase);

            if ($newKeyPhrase !== $existingTranslation->keyPhrase) {
                // Si la frase cambia, necesitamos generar nuevo TTS.
                $shouldRegenerateAudio = true;
                $audioPath = null; // Indicamos que el servicio debe generar y guardar.
            }
        }
        // -----------------------------------------------------------------


        // Mapeamos los campos del request a la entidad
        $updatedEntity = new CardTranslationEntity(
            $existingTranslation->cardTranslationId,
            $existingTranslation->cardId,
            $request->validated('language_code', $existingTranslation->languageCode), 
            $request->validated('key_phrase', $existingTranslation->keyPhrase), 
            $audioPath, // Será la nueva ruta/URL, o null si se espera TTS, o la ruta antigua.
        );

        // Pasamos el ID y el flag de regeneración al servicio.
        $translation = $this->cardTranslationService->updateTranslation($id, $updatedEntity, $shouldRegenerateAudio);

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

        // Se mantiene 'create' para chequear el permiso general (asumiendo que solo los creadores pueden eliminar).
        Gate::authorize('create', CardTranslation::class);

        $deleted = $this->cardTranslationService->deleteTranslation($id);

        if ($deleted) {
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'No se pudo eliminar la traducción'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
