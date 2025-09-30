<?php

namespace App\Http\Controllers;

use App\Core\Repositories\SupabaseMediaStorage;
use App\Core\Services\MediaUploader;
use App\Http\Requests\UploadMediaRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse; // Importar JsonResponse para tipado de retorno

/**
 * @OA\Tag(
 * name="Media",
 * description="Gestión de subida y almacenamiento de archivos multimedia (imágenes, audio)."
 * )
 */
class MediaController extends Controller
{
    /**
     * Sube un archivo multimedia y lo registra en la base de datos.
     *
     * @OA\Post(
     * path="/api/media/upload",
     * tags={"Media"},
     * summary="Sube una imagen o audio al almacenamiento (Supabase/S3).",
     * description="Permite subir archivos multimedia, asignándolos al usuario autenticado o 'anonymous'.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Archivo a subir (debe ser multipart/form-data).",
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * ref="#/components/schemas/UploadMediaRequest"
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Archivo subido y registrado correctamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Archivo subido correctamente"),
     * @OA\Property(property="url", type="string", example="https://storage.example.com/user_1/image/12345.jpg"),
     * @OA\Property(property="id", type="integer", example=1)
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function upload(UploadMediaRequest $request): JsonResponse
    {
        $file = $request->file('file');

        $mime = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
        $type = str_starts_with($mime, 'image/') ? 'image' : 'audio';

        $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
        $path = "{$userFolder}/{$type}/" . uniqid() . '.' . $extension;
        $content = file_get_contents($file->getRealPath());

        // Nota: Asegúrate de que MediaUploader, SupabaseMediaStorage y el modelo Media
        // tienen las dependencias necesarias y la configuración de Supabase.
        $uploader = new MediaUploader(new SupabaseMediaStorage());
        $url = $uploader->upload($path, $content, $mime);

        $media = Media::create([
            'path' => $path,
            'url' => $url,
            'mime' => $mime,
            'type' => $type,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Archivo subido correctamente',
            'url' => $media->url,
            'id' => $media->id,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Muestra la lista de archivos multimedia subidos por el usuario autenticado.
     *
     * @OA\Get(
     * path="/api/media",
     * tags={"Media"},
     * summary="Obtiene la lista de archivos multimedia del usuario.",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Lista de archivos multimedia obtenida con éxito.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/MediaResource")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function index(): JsonResponse
    {
        // Se asume que MediaResource existe y define la estructura de salida.
        $media = Media::where('user_id', Auth::id())->latest()->get();

        // Si tuvieras un MediaResource, usarías:
        // return MediaResource::collection($media);
        return response()->json($media, JsonResponse::HTTP_OK);
    }

    /**
     * Elimina un archivo multimedia por su ID.
     *
     * @OA\Delete(
     * path="/api/media/{id}",
     * tags={"Media"},
     * summary="Elimina un archivo multimedia por ID.",
     * description="Elimina el registro de la base de datos y el archivo del almacenamiento.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del archivo multimedia a eliminar.",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Archivo eliminado con éxito.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Archivo eliminado")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=404, description="Archivo no encontrado o no pertenece al usuario")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $media = Media::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Lógica de eliminación en el disco (Supabase)
        Storage::disk('supabase')->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'Archivo eliminado'], JsonResponse::HTTP_OK);
    }
}
