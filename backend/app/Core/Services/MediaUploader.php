<?php 

// app/Core/Services/MediaUploader.php
namespace App\Core\Services;

use App\Core\Entities\MediaFile;
use App\Core\Interfaces\MediaStorageInterface;

/**
 * Servicio central para la gestión de archivos multimedia (subida, borrado, obtención de URLs).
 * Actúa como una fachada que delega las operaciones al driver de almacenamiento inyectado.
 */
class MediaUploader
{
    /**
     * @var MediaStorageInterface La implementación específica del almacenamiento (ej: Supabase, S3).
     */
    protected MediaStorageInterface $storage;

    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Sube contenido binario a una ruta específica.
     *
     * @param string $path La ruta donde se almacenará el archivo (ej: user_1/audio/file.mp3).
     * @param string $content El contenido binario del archivo.
     * @param string $mime El tipo MIME del archivo.
     * @return string La URL temporal o de acceso al archivo.
     */
    public function upload(string $path, string $content, string $mime): string
    {
        $file = new MediaFile($path, $content, $mime);
        $this->storage->upload($file);
        // Retornamos la URL que el driver considere apropiada (puede ser una URL temporal).
        return $this->storage->getUrl($path);
    }

    /**
     * Elimina un archivo del almacenamiento.
     *
     * @param string $path La ruta del archivo a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function delete(string $path): bool
    {
        return $this->storage->delete($path);
    }
    
    /**
     * Obtiene la URL pública directa del archivo.
     * * Este método es crucial para Supabase, ya que permite acceder a recursos 
     * con permisos 'public' sin necesidad de firmar la URL.
     *
     * @param string $path La ruta del archivo dentro del bucket (ej: user_2/audio/translation_...).
     * @return string La URL pública completa y permanente.
     */
    public function getPublicUrl(string $path): string
    {
        // Delegamos la lógica de construcción de la URL pública a la implementación del storage.
        return $this->storage->getPublicUrl($path);
    }
}
