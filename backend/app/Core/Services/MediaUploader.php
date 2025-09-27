<?php 

// app/Core/Services/MediaUploader.php
namespace App\Core\Services;

use App\Core\Entities\MediaFile;
use App\Core\Interfaces\MediaStorageInterface;

class MediaUploader
{
    protected MediaStorageInterface $storage;

    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function upload(string $path, string $content, string $mime): string
    {
        $file = new MediaFile($path, $content, $mime);
        $this->storage->upload($file);
        return $this->storage->getUrl($path);
    }
}
