<?php

namespace App\Core\Services;

use App\Core\Entities\CardTranslationEntity;
use App\Core\Repositories\CardTranslationRepositoryInterface;
use App\Core\Services\MediaUploader; 
use App\Models\Media; // Necesario para registrar el path en la tabla 'media' local
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CardTranslationService
{
    protected CardTranslationRepositoryInterface $translationRepository;
    protected MediaUploader $mediaUploader; // Inyectado para manejar la subida a Supabase
    
    public function __construct(
        CardTranslationRepositoryInterface $translationRepository,
        MediaUploader $mediaUploader 
    ) {
        $this->translationRepository = $translationRepository;
        $this->mediaUploader = $mediaUploader;
    }

    /**
     * Crea una traducción, generando el archivo de audio TTS y subiéndolo a Supabase.
     * * @param CardTranslationEntity $entity Entidad con cardId, languageCode y keyPhrase.
     * @param bool $generateAudio Indica si se debe intentar generar el audio TTS.
     * @return CardTranslationEntity
     */
    public function createTranslation(CardTranslationEntity $entity, bool $generateAudio = true): CardTranslationEntity
    {
        // 1. Inicialmente, el audioPath es nulo
        $audioPath = $entity->audioPath;

        if ($generateAudio) {
            try {
                // 2. Genera el audio y lo sube a Supabase, obteniendo el path.
                $audioPath = $this->generateAndUploadAudio($entity->keyPhrase, $entity->languageCode);
                $entity->audioPath = $audioPath; // Asignamos el path generado
            } catch (\Exception $e) {
                // Si la generación o subida falla, logueamos y la traducción se guarda sin audio.
                Log::error("TTS/Supabase Falló para '{$entity->keyPhrase}': " . $e->getMessage());
                // Aseguramos que el path sea null para no guardar paths inválidos
                $entity->audioPath = null;
            }
        }

        // 3. Guarda en la base de datos
        return $this->translationRepository->create($entity);
    }

    /**
     * Lógica para actualizar una traducción, con opción a regenerar el audio.
     * * @param int $id ID de la traducción a actualizar.
     * @param CardTranslationEntity $entity Entidad con los datos a actualizar.
     * @return CardTranslationEntity
     */
    public function updateTranslation(int $id, CardTranslationEntity $entity): CardTranslationEntity
    {
        $existing = $this->translationRepository->find($id);

        if (!$existing) {
            throw new ModelNotFoundException("Traducción con ID {$id} no encontrada.");
        }

        $regenerateAudio = false;

        // Si la frase clave o el idioma cambian, debemos regenerar el audio
        if ($entity->keyPhrase !== $existing->keyPhrase || $entity->languageCode !== $existing->languageCode) {
            $regenerateAudio = true;
        }

        if ($regenerateAudio) {
            // 1. Eliminar el archivo antiguo de Supabase si existe
            if ($existing->audioPath) {
                $this->deleteMediaFile($existing->audioPath);
            }

            // 2. Generar y subir el nuevo audio
            try {
                $newPath = $this->generateAndUploadAudio($entity->keyPhrase, $entity->languageCode);
                $entity->audioPath = $newPath;
            } catch (\Exception $e) {
                Log::error("TTS/Supabase Falló al regenerar audio para '{$entity->keyPhrase}': " . $e->getMessage());
                $entity->audioPath = null; // Falla, no guardamos path
            }
        }
        
        // 3. Actualizar la traducción
        return $this->translationRepository->update($id, $entity);
    }
    
    /**
     * Obtiene una traducción por su ID.
     * @param int $id
     * @return ?CardTranslationEntity
     */
    public function getTranslation(int $id): ?CardTranslationEntity
    {
        return $this->translationRepository->find($id);
    }
    
    /**
     * Obtiene todas las traducciones.
     * @return Collection<CardTranslationEntity>
     */
    public function getAllTranslations(): Collection
    {
        return $this->translationRepository->getAll();
    }
    
    /**
     * Elimina una traducción y su archivo de audio asociado (si existe).
     * @param int $id
     * @return bool
     */
    public function deleteTranslation(int $id): bool
    {
        $entity = $this->translationRepository->find($id);

        if ($entity && $entity->audioPath) {
            // Lógica para eliminar el archivo de Supabase y el registro en la tabla 'media'
            $this->deleteMediaFile($entity->audioPath);
        }

        return $this->translationRepository->delete($id);
    }
    
    /**
     * Lógica para eliminar el archivo de media de Supabase y de la BD local.
     * @param string $path El path en Supabase.
     */
    protected function deleteMediaFile(string $path): void
    {
        try {
            // Eliminar el archivo del bucket Supabase
            Storage::disk('supabase')->delete($path);
            
            // Eliminar el registro de la tabla 'media' local
            Media::where('path', $path)->delete(); 
            
            Log::info("Archivo de media eliminado de Supabase y BD: {$path}");
        } catch (\Exception $e) {
            Log::warning("Fallo al eliminar archivo de Supabase: {$path}. Error: " . $e->getMessage());
        }
    }


    // =================================================================
    // LÓGICA CLAVE DE INTEGRACIÓN TTS/SUPABASE
    // =================================================================

    /**
     * Lógica para generar audio TTS y subirlo a Supabase.
     * * @param string $text Texto a convertir a voz.
     * @param string $languageCode Idioma para TTS (ej: 'es', 'en').
     * @return ?string El path del archivo en Supabase (o null si falla).
     */
    protected function generateAndUploadAudio(string $text, string $languageCode): ?string
    {
        // ----------------------------------------------------------------
        // PASO 1: GENERACIÓN DE AUDIO TTS
        // ** INTEGRACIÓN REQUERIDA: Conecta tu API de TTS (ej. Gemini o Google Cloud TTS) aquí. **
        // ----------------------------------------------------------------
        
        $audioBinaryContent = null;
        $mime = 'audio/mpeg'; // Mime type común para audio
        $extension = 'mp3';
        
        // --- INICIO DE SIMULACIÓN ---
        // DEBES REEMPLAZAR ESTE BLOQUE con la llamada a la API TTS real.
        try {
            // Aquí iría tu código de llamada a la API:
            // $audioBinaryContent = $ttsApi->generateAudio($text, $languageCode);
            
            // Por ahora, simulamos un contenido binario.
            $audioBinaryContent = "Simulated binary audio content for: {$text}"; 
            Log::info("Simulación de audio TTS generada.");
            
        } catch (\Exception $e) {
            Log::error("Error en la llamada a la API TTS: " . $e->getMessage());
            // Lanza una excepción para detener el proceso de subida si falla la generación
            throw new \Exception("Fallo al generar audio TTS.");
        }

        if (empty($audioBinaryContent)) {
            Log::warning("El contenido de audio binario devuelto por la API TTS está vacío.");
            return null;
        }

        // ----------------------------------------------------------------
        // PASO 2: SUBIDA A SUPABASE (Usando la lógica de tu MediaController)
        // ----------------------------------------------------------------

        $userFolder = Auth::check() ? 'user_' . Auth::id() : 'anonymous';
        // Limpiamos la frase y la truncamos para un nombre de archivo seguro y descriptivo
        $slug = Str::slug(substr($text, 0, 50)); 
        $path = "{$userFolder}/audio/translations/{$languageCode}/{$slug}-" . uniqid() . ".{$extension}";

        // Subir el contenido binario usando el MediaUploader inyectado
        $url = $this->mediaUploader->upload($path, $audioBinaryContent, $mime);
        
        // 3. Registrar en la tabla 'media' para trazabilidad
        Media::create([
            'path' => $path,
            'url' => $url,
            'mime' => $mime,
            'type' => 'audio',
            'user_id' => Auth::id(),
        ]);
        
        Log::info("Audio subido a Supabase y registrado en BD local: {$path}");

        return $path; // Devolvemos el PATH de Supabase (es lo que guarda la DB)
    }
}
