<?php

namespace App\DTOs\Auth;

use Illuminate\Http\Request;

/**
 * Objeto de Transferencia de Datos (DTO) para la verificación de códigos de seguridad.
 * Se encarga de transportar y tipar los datos necesarios para validar un código enviado por email.
 */
class VerificarCodigoDTO
{
    /**
     * Define las propiedades del DTO utilizando promoción de propiedades del constructor (PHP 8+).
     * * @param string $email Dirección de correo electrónico del usuario.
     * @param string $codigo Código de verificación recibido.
     */
    public function __construct(
        public readonly string $email,
        public readonly string $codigo,
    ) {}

    /**
     * Método de factoría estático para crear una instancia del DTO a partir de una petición HTTP.
     * Realiza una limpieza básica de los datos de entrada.
     * * @param Request $request Petición de entrada de Laravel.
     * @return self Nueva instancia de VerificarCodigoDTO.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // Normaliza el email: minúsculas, elimina espacios y obtiene el valor en cadena
            email:  $request->string('email')->lower()->trim()->value(),
            // Limpia espacios en blanco del código de verificación
            codigo: $request->string('codigo')->trim()->value(),
        );
    }
}
