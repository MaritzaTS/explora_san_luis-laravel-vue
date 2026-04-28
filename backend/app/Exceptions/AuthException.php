<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

// 🔹 Excepción personalizada para el módulo de autenticación
class AuthException extends Exception
{
    // 🔹 Código interno del error (para frontend o manejo interno)
    public string $errorCode;

    // 🔹 Código HTTP de respuesta
    public int $statusCode;

    // 🔹 Constructor base de la excepción
    public function __construct(string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);

        // 🔹 Guarda el código de error personalizado
        $this->errorCode  = $errorCode;

        // 🔹 Guarda el código HTTP
        $this->statusCode = $statusCode;
    }

    // ====================================================
    // 🔹 FACTORIES — Errores del módulo de autenticación
    // ====================================================

    /**
     * 🔹 Error cuando el email ya existe
     */
    public static function emailYaRegistrado(string $email): self
    {
        return new self(
            "El email '{$email}' ya está registrado.",
            'EMAIL_YA_REGISTRADO',
            409 // Conflict
        );
    }

    /**
     * 🔹 Error de credenciales incorrectas
     */
    public static function credencialesInvalidas(): self
    {
        return new self(
            'Email o contraseña incorrectos.',
            'CREDENCIALES_INVALIDAS',
            401 // Unauthorized
        );
    }

    /**
     * 🔹 Usuario no ha verificado su cuenta
     */
    public static function usuarioNoVerificado(): self
    {
        return new self(
            'Debes verificar tu cuenta antes de iniciar sesión.',
            'USUARIO_NO_VERIFICADO',
            403 // Forbidden
        );
    }

    /**
     * 🔹 Código de verificación incorrecto
     */
    public static function codigoIncorrecto(): self
    {
        return new self(
            'El código de verificación es incorrecto.',
            'CODIGO_INCORRECTO',
            400
        );
    }

    /**
     * 🔹 Código expirado
     */
    public static function codigoExpirado(): self
    {
        return new self(
            'El código de verificación expiró. Solicita uno nuevo.',
            'CODIGO_EXPIRADO',
            410 // Gone
        );
    }

    /**
     * 🔹 Demasiados intentos fallidos
     */
    public static function demasiadosIntentos(): self
    {
        return new self(
            'Demasiados intentos fallidos. Solicita un nuevo código.',
            'DEMASIADOS_INTENTOS',
            429 // Too Many Requests
        );
    }

    /**
     * 🔹 Usuario inactivo
     */
    public static function usuarioInactivo(): self
    {
        return new self(
            'Tu cuenta ha sido desactivada.',
            'USUARIO_INACTIVO',
            403
        );
    }

    // ====================================================
    // 🔹 RENDER — Formato JSON unificado de respuesta
    // ====================================================

    /**
     * 🔹 Convierte la excepción en respuesta JSON automática
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
