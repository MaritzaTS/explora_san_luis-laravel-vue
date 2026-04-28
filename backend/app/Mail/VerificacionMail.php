<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 🔹 Clase Mailable para enviar el correo de verificación de cuenta
class VerificacionMail extends Mailable
{
    // 🔹 Traits:
    // Queueable → permite enviar el correo en cola (async)
    // SerializesModels → optimiza serialización de modelos si se usan
    use Queueable, SerializesModels;

    // 🔹 Constructor con propiedades readonly (inmutables)
    public function __construct(
        public readonly string $nombre,
        public readonly string $codigo,
    ) {}

    /**
     * 🔹 Configuración del correo (asunto, remitente, etc.)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // 🔹 Asunto del correo
            subject: 'Verifica tu cuenta - Explora San Luis',
        );
    }

    /**
     * 🔹 Define la vista Blade que se usará para el contenido del correo
     */
    public function content(): Content
    {
        return new Content(
            // 🔹 Vista ubicada en resources/views/emails/verificacion.blade.php
            view: 'emails.verificacion',

            // 🔹 Datos enviados a la vista
            with: [
                'nombre' => $this->nombre,
                'codigo' => $this->codigo,
            ],
        );
    }
}
