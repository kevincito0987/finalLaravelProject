<?php 

// app/Core/Entities/MediaFile.php
namespace App\Core\Entities;

class MediaFile
{
    public string $path;
    public string $content;
    public string $mime;

    public function __construct(string $path, string $content, string $mime)
    {
        $this->path = $path;
        $this->content = $content;
        $this->mime = $mime;
    }
}
