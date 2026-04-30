<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción especializada para el módulo de Sitios Turísticos.
 *
 * Centraliza los errores de negocio relacionados con los puntos de interés,
 * permitiendo una respuesta estandarizada y semántica para la API.
 */
class SitioTuristicoException extends Exception
{
    /** @var string Código de error técnico (slug) para el manejo de lógica en el frontend. */
    public string $errorCode;

    /** @var int Código de estado HTTP de la respuesta. */
    public int $statusCode;

    /**
     * Constructor de la excepción.
     *
     * @param string $message Mensaje descriptivo del error.
     * @param string $errorCode Código único de error (ej: 'SITIO_NOT_FOUND').
     * @param int $statusCode Código HTTP (por defecto 400).
     */
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    /**
     * Factory Method: Error cuando un sitio específico no existe en la base de datos.
     *
     * @param int $id Identificador del sitio buscado.
     * @return self Instancia de la excepción con estado 404.
     */
    public static function noEncontrado(int $id): self
    {
        return new self(
            "El sitio turístico con ID {$id} no fue encontrado.",
            'SITIO_NOT_FOUND',
            404
        );
    }

    /**
     * Método de renderizado automático para Laravel.
     *
     * Transforma la excepción en una respuesta JSON estructurada,
     * eliminando la necesidad de bloques catch repetitivos en los controladores.
     *
     * @return JsonResponse Respuesta JSON con el formato de error de la API.
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
