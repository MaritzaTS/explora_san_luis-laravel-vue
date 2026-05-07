<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Excepción especializada para el módulo de Reseñas.
 */
class ResenaException extends Exception
{
    public string $errorCode;
    public int $statusCode;

    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->errorCode  = $errorCode;
        $this->statusCode = $statusCode;
    }

    public static function noEncontrada(int $id): self
    {
        return new self(
            "La reseña con ID {$id} no fue encontrada.",
            'RESENA_NOT_FOUND',
            404
        );
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $this->getMessage(),
            'error'   => $this->errorCode,
        ], $this->statusCode);
    }
}
