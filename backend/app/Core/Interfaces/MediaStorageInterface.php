<?php 

// app/Core/Interfaces/MediaStorageInterface.php
namespace App\Core\Interfaces;

use App\Core\Entities\MediaFile;

interface MediaStorageInterface
{
    public function upload(MediaFile $file): bool;
    public function getUrl(string $path): string;
}
