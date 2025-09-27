<?php 

// app/Core/Repositories/SupabaseMediaStorage.php
namespace App\Core\Repositories;

use App\Core\Entities\MediaFile;
use App\Core\Interfaces\MediaStorageInterface;
use Illuminate\Support\Facades\Storage;

class SupabaseMediaStorage implements MediaStorageInterface
{
    public function upload(MediaFile $file): bool
    {
        return Storage::disk('supabase')->put($file->path, $file->content);
    }

    public function getUrl(string $path): string
    {
        return config('filesystems.disks.supabase.url') . '/' . ltrim($path, '/');
    }
}
