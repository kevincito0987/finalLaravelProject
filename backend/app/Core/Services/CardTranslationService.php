<?php

namespace App\Core\Services;

use App\Core\Entities\Card\CardTranslationEntity;
use App\Core\Interfaces\CardTranslationRepositoryInterface;
use App\Core\Repositories\SupabaseMediaStorage; 
use App\Models\Media; 
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CardTranslationService
{
    // AÑADIDO: Constantes para construir la URL pública de Supabase. 
    // Estas DEBEN coincidir con la configuración de tu storage.
    protected const SUPABASE_BASE_URL = 'https://abcdefghijk.supabase.co/storage/v1/object/public/';
    protected const BUCKET_NAME = 'media'; 

    protected CardTranslationRepositoryInterface $translationRepository;
    // Mantenemos la inyección de MediaUploader, pero SOLO usaremos upload/delete, NO getPublicUrl
    protected $mediaUploader; 
    
    public function __construct(
        CardTranslationRepositoryInterface $translationRepository,
        // Asumimos que $mediaUploader es la clase que maneja la subida y eliminación real
        $mediaUploader 
    ) {
        $this->translationRepository = $translationRepository;
        $this->mediaUploader = $mediaUploader;
    }

    // =================================================================
    // MÉTODOS PÚBLICOS DE NEGOCIO (Sin cambios en la lógica)
    // =================================================================
    
    /**
     * Crea una traducción. La prioridad de audio es: Path provisto > TTS (si se solicita).
     */
    public function createTranslation(CardTranslationEntity $entity, bool $generateAudio = true): CardTranslationEntity
    {
        if ($generateAudio && empty($entity->audioPath)) {
            try {
                $audioPath = $this->generateAndUploadAudio($entity->keyPhrase, $entity->languageCode);
                $entity->audioPath = $audioPath;
            } catch (\Exception $e) {
                Log::error("TTS/Supabase Falló para '{$entity->keyPhrase}': " . $e->getMessage());
                $entity->audioPath = null;
            }
        }
        
        return $this->translationRepository->create($entity);
    }

    /**
     * Lógica para actualizar una traducción.
     */
    public function updateTranslation(
        int $id, 
        CardTranslationEntity $entity, 
        bool $regenerateAudio = false
    ): CardTranslationEntity
    {
        $existing = $this->translationRepository->find($id);

        if (!$existing) {
            throw new ModelNotFoundException("Traducción con ID {$id} no encontrada.");
        }

        $oldPath = $existing->audioPath;
        $newPath = $entity->audioPath; 
        
        // ---------------------------------------------------------------------
        // 1. Decidir el Path de Audio Final
        // ---------------------------------------------------------------------
        
        if ($regenerateAudio) {
            try {
                $newPath = $this->generateAndUploadAudio($entity->keyPhrase, $entity->languageCode);
            } catch (\Exception $e) {
                Log::error("TTS/Supabase Falló al regenerar audio para '{$entity->keyPhrase}': " . $e->getMessage());
                $newPath = null;
            }
        } 
        
        elseif (empty($entity->audioPath)) {
            $newPath = $oldPath;
        }

        // ---------------------------------------------------------------------
        // 2. Limpieza y Registro
        // ---------------------------------------------------------------------
        
        if ($oldPath && $oldPath !== $newPath && SupabaseMediaStorage::isSupabasePath($oldPath)) {
            $this->deleteMediaFile($oldPath);
        }
        
        if (SupabaseMediaStorage::isSupabasePath($newPath)) {
            $this->ensureMediaRecordExists($newPath, $entity->keyPhrase, $entity->languageCode);
        }

        // ---------------------------------------------------------------------
        // 3. Persistir
        // ---------------------------------------------------------------------
        
        $entity->audioPath = $newPath;
        $entity->cardTranslationId = $id;

        return $this->translationRepository->update($id, $entity);
    }
    
    /**
     * Obtiene una traducción por su ID.
     */
    public function getTranslation(int $id): ?CardTranslationEntity
    {
        return $this->translationRepository->find($id);
    }
    
    /**
     * Obtiene todas las traducciones.
     */
    public function getAllTranslations(): Collection
    {
        return $this->translationRepository->getAll(); 
    }
    
    /**
     * Elimina una traducción y su archivo de audio asociado.
     */
    public function deleteTranslation(int $id): bool
    {
        $entity = $this->translationRepository->find($id);

        if ($entity && $entity->audioPath && SupabaseMediaStorage::isSupabasePath($entity->audioPath)) {
            $this->deleteMediaFile($entity->audioPath);
        }

        return $this->translationRepository->delete($id);
    }
    
    // =================================================================
    // MÉTODOS PROTEGIDOS DE MEDIA
    // =================================================================
    
    /**
     * Lógica para eliminar el archivo de media de Supabase y de la BD local.
     */
    protected function deleteMediaFile(string $path): void
    {
        try {
            $this->mediaUploader->delete($path);
            Media::where('path', $path)->delete(); 
            
            Log::info("Archivo de media eliminado de Supabase y BD: {$path}");
        } catch (\Exception $e) {
            Log::warning("Fallo al eliminar archivo de Supabase: {$path}. Error: " . $e->getMessage());
        }
    }

    /**
     * Construye la URL pública de Supabase a partir del path de almacenamiento.
     * REEMPLAZA AL MÉTODO 'getPublicUrl' que no existe.
     * @param string $path El path del archivo DENTRO del bucket.
     * @return string La URL completa y accesible.
     */
    protected function buildSupabasePublicUrl(string $path): string
    {
        // Construcción directa usando las constantes definidas en el servicio
        return self::SUPABASE_BASE_URL . self::BUCKET_NAME . '/' . $path;
    }

    /**
     * Asegura que exista un registro en la tabla 'media' para un path de Supabase.
     */
    protected function ensureMediaRecordExists(string $path, string $keyPhrase, string $languageCode): void
    {
        if (Media::where('path', $path)->exists()) {
            return;
        }

        try {
            // ** CORRECCIÓN APLICADA AQUÍ: Se llama a la función interna buildSupabasePublicUrl **
            $url = $this->buildSupabasePublicUrl($path); 
            $mime = 'audio/mpeg'; 

            Media::create([
                'path' => $path,
                'url' => $url,
                'mime' => $mime,
                'type' => 'audio',
                'user_id' => Auth::id(),
                'metadata' => [
                    'translation_phrase' => $keyPhrase, 
                    'language_code' => $languageCode
                ]
            ]);
            Log::info("Registro local de media creado/actualizado para path de Supabase: {$path}");
        } catch (\Exception $e) {
            Log::error("Fallo al crear registro de media local para {$path}: " . $e->getMessage());
        }
    }


    /**
     * Lógica para generar audio TTS y subirlo a Supabase.
     */
    protected function generateAndUploadAudio(string $text, string $languageCode): ?string
    {
        // ----------------------------------------------------------------
        // PASO 1: GENERACIÓN DE AUDIO TTS (SIMULACIÓN)
        // ----------------------------------------------------------------
        
        $audioBinaryContent = null;
        $mime = 'audio/mpeg'; 
        $extension = 'mp3';
        
        try {
            if (strlen($text) < 3) {
                throw new \Exception("Texto demasiado corto para TTS.");
            }
            $audioBinaryContent = "Simulated binary audio content for: {$text} in {$languageCode}"; 
            Log::info("Simulación de audio TTS generada.");

        } catch (\Exception $e) {
            Log::error("Error en la llamada a la API TTS: " . $e->getMessage());
            throw new \Exception("Fallo al generar audio TTS.");
        }

        if (empty($audioBinaryContent)) {
            Log::warning("El contenido de audio binario devuelto por la API TTS está vacío.");
            return null;
        }

        // ----------------------------------------------------------------
        // PASO 2: SUBIDA A SUPABASE
        // ----------------------------------------------------------------

        $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
        $slug = Str::slug(substr($text, 0, 50)); 
        $path = "{$userFolder}/audio/translations/{$languageCode}/{$slug}-" . uniqid() . ".{$extension}";

        $this->mediaUploader->upload($path, $audioBinaryContent, $mime);
        
        $this->ensureMediaRecordExists($path, $text, $languageCode);

        return $path;
    }
}
