<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRegisteredMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param \App\Models\User $user El modelo de usuario que fue registrado.
     */
    public function __construct(public User $user) {}

    /**
     * Obtiene la envoltura del mensaje.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido Administrador! Tu cuenta está lista.',
        );
    }

    /**
     * Obtiene la definición de contenido del mensaje.
     */
    public function content(): Content
    {
        // Se espera una vista Markdown en resources/views/mail/admin/registered.blade.php
        return new Content(
            markdown: 'mail.admin.registered',
        );
    }
    
    /**
     * Obtiene los archivos adjuntos para el mensaje.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
