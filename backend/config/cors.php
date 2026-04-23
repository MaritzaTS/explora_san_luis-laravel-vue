<?php

return [

    // 🔹 Rutas a las que se les aplicará la configuración CORS
    // En este caso, todas las rutas que comiencen con "api/"
    'paths' => ['api/*'],

    // 🔹 Métodos HTTP permitidos (GET, POST, PUT, DELETE, etc.)
    // '*' significa que se permiten todos los métodos
    'allowed_methods' => ['*'],

    // 🔹 Orígenes permitidos (dominios que pueden consumir la API)
    // Se toma desde el .env (FRONTEND_URL) o usa localhost por defecto
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],

    // 🔹 Patrones de orígenes permitidos (expresiones tipo regex)
    // Vacío porque no se están usando patrones dinámicos
    'allowed_origins_patterns' => [],

    // 🔹 Headers permitidos en las peticiones
    // '*' permite todos los headers (Authorization, Content-Type, etc.)
    'allowed_headers' => ['*'],

    // 🔹 Headers que el navegador puede exponer al frontend
    // Vacío porque no necesitas exponer ninguno adicional
    'exposed_headers' => [],

    // 🔹 Tiempo (en segundos) que el navegador cachea la respuesta CORS
    // 0 significa que no se cachea
    'max_age' => 0,

    // 🔹 Indica si se permiten credenciales (cookies, tokens en headers)
    // TRUE es necesario si usas autenticación con cookies o sesiones
    'supports_credentials' => true, // CRÍTICO para que las cookies funcionen
];
