<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción personalizada para el módulo de Eventos.
 *
 * Centraliza la gestión de errores de negocio, permitiendo devolver
 * códigos de error semánticos (EVENTO_NOT_FOUND) y estados HTTP correctos.
 */
class EventoException extends Exception
{
    /** @var string Código interno para que el Frontend identifique el error fácilmente. */
    public string $errorCode;

    /** @var int Código de estado HTTP (400, 404, 403, etc.). */
    public int $statusCode;

    /**
     * Constructor de la excepción.
     *
     * @param string $message Mensaje legible para el usuario.
     * @param string $errorCode Código de error en formato STRING.
     * @param int $statusCode Código HTTP (por defecto 400 Bad Request).
     */
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    /**
     * Factory Method: Error cuando un evento no existe.
     *
     * @param int $id ID del evento buscado.
     * @return self
     */
    public static function noEncontrado(int $id): self
    {
        return new self(
            "El evento con ID {$id} no fue encontrado.",
            'EVENTO_NOT_FOUND',
            404
        );
    }

    /**
     * Renderiza la excepción en una respuesta JSON automática.
     *
     * Laravel detecta este método y lo utiliza para transformar el 'throw'
     * directamente en esta estructura de respuesta.
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
