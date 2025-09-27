<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class SyncSupabaseMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:sync-supabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza archivos del bucket Supabase con la base de datos';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('🔄 Iniciando sincronización con Supabase...');

        $files = Storage::disk('supabase')->allFiles();

        foreach ($files as $path) {
            if (Media::where('path', $path)->exists()) {
                $this->line("✅ Ya registrado: {$path}");
                continue;
            }

            $url = config('filesystems.disks.supabase.url') . '/' . $path;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mime = $this->guessMime($extension);
            $type = str_starts_with($mime, 'image/') ? 'image' : (str_starts_with($mime, 'audio/') ? 'audio' : 'unknown');

            Media::create([
                'path' => $path,
                'url' => $url,
                'mime' => $mime,
                'type' => $type,
                'user_id' => null, // No se puede inferir desde el path sin parsing adicional
            ]);

            $this->info("📦 Registrado: {$path}");
        }

        $this->info('✅ Sincronización completada.');
    }

    /**
     * Adivina el MIME a partir de la extensión.
     */
    protected function guessMime(string $extension): string
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            default => 'application/octet-stream',
        };
    }
}
