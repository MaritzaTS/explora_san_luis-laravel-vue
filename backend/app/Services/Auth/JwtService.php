<?php

namespace App\Services\Auth;

use App\Models\Usuario;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Servicio encargado de la gestión de tokens de autenticación JSON Web Token (JWT).
 */
class JwtService
{
    /**
     * Genera un token de acceso a partir de una instancia de usuario.
     * * @param Usuario $usuario Instancia del modelo de usuario autenticado.
     * @return string Token JWT generado.
     */
    public function generarToken(Usuario $usuario): string
    {
        // Utiliza la fachada JWTAuth para crear el token basado en las credenciales del usuario
        return JWTAuth::fromUser($usuario);
    }
}
