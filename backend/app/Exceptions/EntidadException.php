<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción personalizada para la lógica de negocio de Entidades.
 *
 * Gestiona errores específicos como fallos en la jerarquía de categorías,
 * inconsistencias en los subtipos o registros inexistentes.
 */
class EntidadException extends Exception
{
    /** @var string Código de error único para identificación técnica en el frontend. */
    public string $errorCode;

    /** @var int Código de estado HTTP. */
    public int $statusCode;

    /**
     * Constructor base de la excepción.
     *
     * @param string $message Mensaje descriptivo para el usuario.
     * @param string $errorCode Identificador de error (ej: 'SUBTIPOS_INVALIDOS').
     * @param int $statusCode Código HTTP (default 400).
     */
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    /**
     * Error cuando no se encuentra un registro por su ID.
     * * @param int $id El ID de la entidad buscada.
     * @return self Respuesta 404 (Not Found).
     */
    public static function noEncontrada(int $id): self
    {
        return new self(
            "La entidad con ID {$id} no fue encontrada.",
            'ENTIDAD_NOT_FOUND',
            404
        );
    }

    /**
     * Error cuando el tipo de entidad (Categoría) no es válido o no existe.
     * * @return self Respuesta 422 (Unprocessable Entity).
     */
    public static function tipoInvalido(): self
    {
        return new self(
            'El tipo de entidad especificado no existe.',
            'TIPO_ENTIDAD_INVALIDO',
            422
        );
    }

    /**
     * Error de integridad relacional: los subtipos enviados no pertenecen a la categoría padre.
     * * Muy útil para validar que un restaurante no intente usar subtipos de hoteles.
     *
     * @return self Respuesta 422 (Unprocessable Entity).
     */
    public static function subtiposInvalidos(): self
    {
        return new self(
            'Uno o más subtipos no pertenecen al tipo de entidad seleccionado.',
            'SUBTIPOS_INVALIDOS',
            422
        );
    }

    /**
     * Renderiza la excepción en una respuesta JSON estandarizada.
     * * Este método es invocado automáticamente por el Exception Handler de Laravel.
     *
     * @return JsonResponse Estructura de error coherente con el resto de la API.
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
