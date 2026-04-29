<?php

namespace App\DTOs\Auth;

use Illuminate\Http\Request;

/**
 * Objeto de Transferencia de Datos (DTO) para el proceso de inicio de sesión.
 * Centraliza las credenciales de acceso para asegurar que los datos
 * se transporten de forma íntegra y tipada a través de la aplicación.
 */
class LoginDTO
{
    /**
     * Inicializa el DTO con propiedades de solo lectura.
     * * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña de la cuenta.
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    /**
     * Crea una instancia de LoginDTO a partir de los datos de la petición HTTP.
     * * @param Request $request Petición entrante de Laravel.
     * @return self Nueva instancia con los datos procesados.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // Normaliza el email para evitar errores por mayúsculas o espacios accidentales
            email:    $request->string('email')->lower()->trim()->value(),
            // Captura la contraseña directamente para no alterar caracteres especiales
            password: $request->input('password'),
        );
    }
}
