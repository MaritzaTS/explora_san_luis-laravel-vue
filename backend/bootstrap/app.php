<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

// 🔹 Configuración principal de la aplicación (Laravel bootstrap moderno)
return Application::configure(basePath: dirname(__DIR__))

    // ====================================================
    // 🔹 CONFIGURACIÓN DE RUTAS
    // ====================================================
    ->withRouting(
        web: __DIR__.'/../routes/web.php',       // rutas web (sesión, vistas, etc.)
        api: __DIR__.'/../routes/api.php',       // rutas API (JSON)
        commands: __DIR__.'/../routes/console.php', // comandos Artisan
        health: '/up', // endpoint de salud (health check)
    )

    // ====================================================
    // 🔹 MIDDLEWARES
    // ====================================================
    ->withMiddleware(function (Middleware $middleware) {
        // 🔹 Aquí puedes registrar middlewares globales o por grupo
    })

    // ====================================================
    // 🔹 MANEJO GLOBAL DE EXCEPCIONES
    // ====================================================
    ->withExceptions(function (Exceptions $exceptions) {

        // ====================================================
        // 🔹 RESPUESTAS JSON CONSISTENTES PARA TODA LA API
        // ====================================================

        /**
         * 🔹 ERRORES DE VALIDACIÓN (FormRequest o Validator)
         * Ej: campos requeridos, formatos inválidos, etc.
         */
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Los datos enviados no son válidos.',
                    'error'   => 'VALIDATION_ERROR',
                    'errors'  => $e->errors(), // detalle por campo
                ], 422);
            }
        });

        /**
         * 🔹 MODELO NO ENCONTRADO
         * Ej: User::findOrFail(999)
         */
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Recurso no encontrado.',
                    'error'   => 'RESOURCE_NOT_FOUND',
                ], 404);
            }
        });

        /**
         * 🔹 RUTA NO EXISTE
         * Ej: /api/loquesea que no está definida
         */
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Endpoint no encontrado.',
                    'error'   => 'ROUTE_NOT_FOUND',
                ], 404);
            }
        });

        /**
         * 🔹 MÉTODO HTTP NO PERMITIDO
         * Ej: enviar POST a una ruta que solo acepta GET
         */
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Método HTTP no permitido para esta ruta.',
                    'error'   => 'METHOD_NOT_ALLOWED',
                ], 405);
            }
        });

        /**
         * 🔹 USUARIO NO AUTENTICADO
         * Ej: token JWT inválido o ausente
         */
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No autenticado.',
                    'error'   => 'UNAUTHENTICATED',
                ], 401);
            }
        });

    })

    // 🔹 Crea la instancia final de la aplicación
    ->create();
