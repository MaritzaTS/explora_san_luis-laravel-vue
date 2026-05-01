<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción personalizada para la gestión de Usuarios.
 *
 * Centraliza errores de autenticación, permisos y reglas de integridad,
 * permitiendo un flujo de control limpio mediante el método render de Laravel.
 */
class UsuarioException extends Exception
{
    /** @var string Código interno para identificación rápida en el Frontend. */
    public string $errorCode;

    /** @var int Código de estado HTTP. */
    public int $statusCode;

    /**
     * Constructor base de la excepción.
     *
     * @param string $message Mensaje descriptivo del error.
     * @param string $errorCode Identificador alfanumérico del error.
     * @param int $statusCode Código HTTP (400 por defecto).
     */
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    /**
     * Error cuando no se localiza un usuario en la persistencia.
     *
     * @param int $id ID del usuario buscado.
     * @return self
     */
    public static function noEncontrado(int $id): self
    {
        return new self(
            "El usuario con ID {$id} no fue encontrado.",
            'USUARIO_NOT_FOUND',
            404
        );
    }

    /**
     * Error de seguridad para prevenir el bloqueo del sistema.
     *
     * Impide que por error o malicia se desactive al administrador raíz,
     * lo cual dejaría el panel de administración inaccesible.
     *
     * @return self
     */
    public static function noSePuedeDesactivarAdmin(): self
    {
        return new self(
            'No se puede desactivar la cuenta del administrador principal.',
            'ADMIN_PROTEGIDO',
            403
        );
    }

    /**
     * Transforma automáticamente la excepción en una respuesta JSON.
     *
     * Laravel invoca este método cuando la excepción no es capturada,
     * estandarizando la salida de error para la API.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $this->getMessage(),
            'error'   => $this->errorCode,
        ], $this->statusCode);
    }
}
