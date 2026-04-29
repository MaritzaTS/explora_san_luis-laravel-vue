<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsuarioResource;
use App\Integrations\Google\GoogleAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para gestionar el flujo de autenticación OAuth2 con Google.
 * Proporciona los endpoints para redirección y procesamiento del retorno (callback).
 */
class GoogleAuthController extends Controller
{
    // Trait personalizado para estandarizar las respuestas JSON de la API
    use ApiResponse;

    /**
     * Inyección de dependencias del servicio de Google.
     * * @param GoogleAuthService $googleAuthService Lógica de integración con Google.
     */
    public function __construct(
        private readonly GoogleAuthService $googleAuthService,
    ) {}

    /**
     * Endpoint: GET /api/auth/google/redirect
     * Obtiene la URL oficial de Google para que el frontend redirija al usuario.
     * * @return JsonResponse URL de autorización envuelta en una respuesta de éxito.
     */
    public function redirect(): JsonResponse
    {
        // Solicita al servicio la URL generada por Socialite
        $url = $this->googleAuthService->getRedirectUrl();

        return $this->success(
            ['url' => $url],
            'URL de autenticación con Google.'
        );
    }

    /**
     * Endpoint: GET /api/auth/google/callback
     * Procesa la información del usuario devuelta por Google tras la autorización.
     * * @return JsonResponse Datos del usuario autenticado y su token JWT.
     */
    public function callback(): JsonResponse
    {
        // Ejecuta la lógica de negocio para registrar o vincular al usuario
        $resultado = $this->googleAuthService->handleCallback();

        return $this->success(
            [
                // Transforma el modelo Usuario a un formato JSON específico mediante el Resource
                'usuario' => new UsuarioResource($resultado['usuario']),
                'token'   => $resultado['token'],
            ],
            'Inicio de sesión con Google exitoso.'
        );
    }
}
