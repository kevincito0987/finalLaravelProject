<?php 

// app/Core/Interfaces/MediaStorageInterface.php
namespace App\Core\Interfaces;

use App\Core\Entities\MediaFile;

interface MediaStorageInterface
{
    public function upload(MediaFile $file): bool;
    public function getUrl(string $path): string;
    public function delete(string $path): bool;
    /**
     * Obtiene la URL pública directa para un archivo.
     */
    public function getPublicUrl(string $path): string; // <-- Nuevo método
}
