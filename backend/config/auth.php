<?php

use App\Models\Usuario;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Aquí defines la configuración por defecto de autenticación:
    | - guard: el tipo de autenticación que se usará por defecto
    | - passwords: el "broker" para resetear contraseñas
    |
    */

    'defaults' => [
        // 🔹 Guard por defecto (web o api)
        // Se toma del .env o usa 'web' por defecto
        'guard' => env('AUTH_GUARD', 'web'),

        // 🔹 Configuración de reseteo de contraseña
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Los guards definen CÓMO se autentican los usuarios.
    | Cada guard usa un driver y un provider.
    |
    | Ejemplo:
    | - web → sesiones (login tradicional con cookies)
    | - api → JWT (tokens para APIs)
    |
    */

    'guards' => [

        // 🔹 Guard para aplicaciones web (login con sesión)
        'web' => [
            'driver' => 'session', // usa sesiones (cookies)
            'provider' => 'users', // usa el provider "users"
        ],

        // 🔹 Guard para API (login con JWT)
        'api' => [
            'driver' => 'jwt', // usa JWT (requiere librería como tymon/jwt-auth)
            'provider' => 'users', // usa el mismo provider
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Los providers definen DE DÓNDE salen los usuarios.
    | Normalmente se usa Eloquent (modelo User).
    |
    */

    'providers' => [

        // 🔹 Provider principal de usuarios
        'users' => [
            'driver' => 'eloquent', // usa Eloquent ORM
            'model' => env('AUTH_MODEL', App\Models\Usuario::class), // modelo Usuario
        ],

        // 🔸 Alternativa (comentada): usar base de datos directa
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Configuración para el sistema de recuperación de contraseñas.
    |
    */

    'passwords' => [

        'users' => [

            // 🔹 Provider que se usará para buscar usuarios
            'provider' => 'users',

            // 🔹 Tabla donde se guardan los tokens de recuperación
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),

            // 🔹 Tiempo de expiración del token (en minutos)
            'expire' => 60,

            // 🔹 Tiempo de espera entre solicitudes (en segundos)
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Tiempo (en segundos) antes de que Laravel vuelva a pedir confirmación
    | de contraseña en acciones sensibles.
    |
    */

    // 🔹 10800 segundos = 3 horas
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
