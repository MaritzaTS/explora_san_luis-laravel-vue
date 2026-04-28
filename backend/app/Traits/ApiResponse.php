<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

// 🔹 Trait reutilizable para estandarizar respuestas JSON en la API
trait ApiResponse
{
    /**
     * 🔹 Respuesta exitosa genérica
     */
    protected function success(
        mixed $data = null,
        string $message = 'Operación exitosa.',
        int $statusCode = 200
    ): JsonResponse {
        // 🔹 Estructura base de respuesta
        $response = [
            'status'  => 'success',
            'message' => $message,
        ];

        // 🔹 Si hay data, se agrega a la respuesta
        if ($data !== null) {

            // 🔹 Si es un Resource (JsonResource),
            // se resuelve a array para evitar doble envoltura (data dentro de data)
            $response['data'] = $data instanceof JsonResource
                ? $data->resolve()
                : $data;
        }

        // 🔹 Retorna respuesta JSON con el status code indicado
        return response()->json($response, $statusCode);
    }

    /**
     * 🔹 Respuesta de creación exitosa (HTTP 201)
     */
    protected function created(
        mixed $data = null,
        string $message = 'Recurso creado exitosamente.'
    ): JsonResponse {
        // 🔹 Reutiliza el método success con código 201
        return $this->success($data, $message, 201);
    }

    /**
     * 🔹 Respuesta sin contenido (usada típicamente en DELETE)
     */
    protected function noContent(string $message = 'Recurso eliminado exitosamente.'): JsonResponse
    {
        // ⚠️ Nota: aunque conceptualmente sería 204 (No Content),
        // aquí se usa 200 para poder enviar un mensaje en el body
        return response()->json([
            'status'  => 'success',
            'message' => $message,
        ], 200);
    }

    /**
     * 🔹 Respuesta de error genérica
     */
    protected function error(
        string $message = 'Ha ocurrido un error.',
        string $errorCode = 'SERVER_ERROR',
        int $statusCode = 400
    ): JsonResponse {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'error'   => $errorCode,
        ], $statusCode);
    }
}
