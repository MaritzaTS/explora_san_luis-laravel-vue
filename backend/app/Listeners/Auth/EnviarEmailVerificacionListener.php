<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UsuarioRegistrado;
use App\Mail\VerificacionMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// 🔹 Listener que escucha el evento de usuario registrado
// y se encarga de enviar el email de verificación
class EnviarEmailVerificacionListener
{
    /**
     * 🔹 Método que se ejecuta automáticamente cuando se dispara el evento
     */
    public function handle(UsuarioRegistrado $event): void
    {
        try {
            // 🔹 Envía el correo al email del usuario
            Mail::to($event->usuario->email)->send(

                // 🔹 Construye el Mailable con los datos necesarios
                new VerificacionMail(
                    nombre: $event->usuario->nombre,
                    codigo: $event->codigo,
                )
            );

        } catch (\Exception $e) {

            // 🔹 En caso de error, lo registra en logs para debugging
            Log::error('Error al enviar email de verificación', [
                'usuario_id' => $event->usuario->id,
                'email'      => $event->usuario->email,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
