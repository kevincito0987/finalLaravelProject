<?php

namespace App\Http\Controllers;

use App\Core\Repositories\SupabaseMediaStorage;
use App\Core\Services\MediaUploader;
use App\Http\Requests\UploadMediaRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    /**
     * Sube un archivo multimedia y lo registra en la base de datos.
     *
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
    
    public function index(): JsonResponse
    {
        // Se asume que MediaResource existe y define la estructura de salida.
        $media = Media::where('user_id', Auth::id())->latest()->get();

        // Si tuvieras un MediaResource, usarías:
        // return MediaResource::collection($media);
        return response()->json($media, JsonResponse::HTTP_OK);
    }
    public function destroy($id): JsonResponse
    {
        $media = Media::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Lógica de eliminación en el disco (Supabase)
        Storage::disk('supabase')->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'Archivo eliminado'], JsonResponse::HTTP_OK);
    }
}
