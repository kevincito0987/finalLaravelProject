<?php

namespace App\Http\Controllers;

use App\Core\Repositories\SupabaseMediaStorage;
use App\Core\Services\MediaUploader;
use App\Http\Requests\UploadMediaRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class MediaController extends Controller
{
    public function upload(UploadMediaRequest $request)
    {
        $file = $request->file('file');

        $mime = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
        $type = str_starts_with($mime, 'image/') ? 'image' : 'audio';

        $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
        $path = "{$userFolder}/{$type}/" . uniqid() . '.' . $extension;
        $content = file_get_contents($file->getRealPath());

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
        ], 201);
    }

    public function index()
    {
        $media = Media::where('user_id', Auth::id())->latest()->get();

        return response()->json($media);
    }
    public function destroy($id)
    {
        $media = Media::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        Storage::disk('supabase')->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'Archivo eliminado']);
    }


}
