<?php 

// app/Core/Repositories/SupabaseMediaStorage.php
namespace App\Core\Repositories;

use App\Core\Entities\MediaFile;
use App\Core\Interfaces\MediaStorageInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Implementación de MediaStorageInterface para Supabase.
 * Contiene lógica auxiliar para manejar rutas específicas del bucket.
 */
class SupabaseMediaStorage implements MediaStorageInterface
{
    /**
     * @var string La URL base de la API de Supabase (ej: https://<project_ref>.supabase.co)
     */
    protected string $supabaseUrl;
    
    /**
     * @var string El nombre del bucket.
     */
    protected string $bucketName = 'laravel'; 

    public function __construct()
    {
        // 1. Intentamos obtener la URL de configuración específica del disco 'supabase'
        $diskUrl = config('filesystems.disks.supabase.url');
        
        // 2. Si no existe, usamos VITE_SUPABASE_URL del .env (preferiblemente configurado en config/services.php)
        $this->supabaseUrl = $diskUrl 
                             ?? env('VITE_SUPABASE_URL') 
                             ?? ''; // Aseguramos que siempre sea una cadena para evitar el TypeError
                             
        // 3. Obtenemos el nombre del bucket de la configuración del disco si está disponible
        $this->bucketName = config('filesystems.disks.supabase.bucket') ?? env('SUPABASE_BUCKET', 'laravel');
    }
    
    /**
     * Sube el contenido de un archivo al bucket de Supabase.
     * @param MediaFile $file
     * @return bool
     */
    public function upload(MediaFile $file): bool
    {
        return Storage::disk('supabase')->put($file->path, $file->content);
    }

    /**
     * Obtiene la URL temporal/completa si el disco la proporciona.
     * @param string $path La ruta interna del archivo en Supabase.
     * @return string
     */
    public function getUrl(string $path): string
    {
        // Esto normalmente retorna una URL firmada o la URL completa basada en la config de Laravel.
        // Si no está correctamente configurado para generar URLs, usamos la lógica de getPublicUrl.
        $diskUrl = config('filesystems.disks.supabase.url');

        if ($diskUrl) {
            return rtrim($diskUrl, '/') . '/' . ltrim($path, '/');
        }

        // Si la URL del disco no está definida, retornamos la URL pública por defecto.
        return $this->getPublicUrl($path);
    }

    /**
     * Elimina un archivo del bucket de Supabase.
     * @param string $path La ruta interna del archivo a eliminar.
     * @return bool
     */
    public function delete(string $path): bool
    {
        return Storage::disk('supabase')->delete($path);
    }

    /**
     * Determina si la ruta proporcionada es una URL de Supabase o una ruta interna del bucket.
     * @param string $path La ruta o URL a verificar.
     * @return bool Devuelve true si es una ruta interna (path) de Supabase 
     * y false si es una URL externa o completa.
     */
    public static function isSupabasePath(string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        // Se considera un path interno si no comienza con http:// o https://
        return !Str::startsWith($path, ['http://', 'https://']);
    }

    /**
     * Obtiene la URL pública directa para un archivo de Supabase Storage.
     * * El formato es: {SUPABASE_URL}/storage/v1/object/public/{BUCKET_NAME}/{PATH}
     *
     * @param string $path La ruta del archivo dentro del bucket (ej: user_2/audio/translation_...).
     * @return string La URL pública completa.
     */
    public function getPublicUrl(string $path): string
    {
        // Nos aseguramos de tener la URL base limpia
        $baseUrl = rtrim($this->supabaseUrl, '/');
        
        // Nos aseguramos de tener el path limpio (sin barras al inicio)
        $path = ltrim($path, '/');

        // Construimos la URL pública final
        return "{$baseUrl}/{$path}";
    }
}
