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
     * Redirige al frontend con el token y datos del usuario como query params.
     */
    public function callback()
    {
        try {
            $resultado = $this->googleAuthService->handleCallback();
            $token = $resultado['token'];
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

            return redirect("{$frontendUrl}/auth/google/callback?token={$token}");
        } catch (\Exception $e) {
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

            return redirect("{$frontendUrl}/auth/google/callback?error=google_auth_failed");
        }
    }
}
