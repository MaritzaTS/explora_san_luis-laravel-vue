<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción personalizada para la gestión de errores multimedia.
 *
 * Centraliza los fallos relacionados con la carga, formato y almacenamiento de imágenes,
 * permitiendo una respuesta coherente en toda la API.
 */
class ImagenException extends Exception
{
    /** @var string Código de error interno para que el frontend pueda identificar el problema sin depender del mensaje. */
    public string $errorCode;

    /** @var int Código de estado HTTP (400, 422, 500, etc.). */
    public int $statusCode;

    /**
     * Constructor de la excepción.
     *
     * @param string $message Mensaje amigable para el usuario.
     * @param string $errorCode Código técnico (ej: 'IMAGEN_TAMANO_EXCEDIDO').
     * @param int $statusCode Código de respuesta HTTP.
     */
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    /**
     * Factory Method: Error genérico de escritura en disco.
     * @return self Respuesta 500.
     */
    public static function falloAlSubir(): self
    {
        return new self(
            'No se pudo subir la imagen. Intenta de nuevo.',
            'IMAGEN_UPLOAD_FAILED',
            500
        );
    }

    /**
     * Factory Method: Fallo de validación de extensiones.
     * @return self Respuesta 422.
     */
    public static function formatoNoPermitido(): self
    {
        return new self(
            'Formato de imagen no permitido. Solo JPG, PNG y WEBP.',
            'IMAGEN_FORMATO_INVALIDO',
            422
        );
    }

    /**
     * Factory Method: Fallo por peso excesivo del archivo.
     * @return self Respuesta 422.
     */
    public static function tamanoExcedido(): self
    {
        return new self(
            'La imagen excede el tamaño máximo permitido.',
            'IMAGEN_TAMANO_EXCEDIDO',
            422
        );
    }

    /**
     * Método de renderizado automático de Laravel.
     *
     * Al existir este método, Laravel capturará automáticamente la excepción y
     * devolverá esta estructura JSON sin necesidad de manejarla manualmente en el Handler.
     *
     * @return JsonResponse Estructura de error estandarizada.
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
