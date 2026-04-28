<?php

namespace App\Events\Auth;

use App\Models\Usuario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// 🔹 Evento que se dispara cuando un usuario se registra
class UsuarioRegistrado
{
    // 🔹 Traits:
    // Dispatchable → permite disparar el evento fácilmente (event())
    // SerializesModels → optimiza la serialización del modelo Usuario
    use Dispatchable, SerializesModels;

    // 🔹 Constructor con propiedades readonly (inmutables)
    public function __construct(
        public readonly Usuario $usuario, // 🔹 Usuario recién registrado
        public readonly string $codigo,   // 🔹 Código de verificación generado
    ) {}
}
