<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Integrations\Google\GoogleAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly GoogleAuthService $googleAuthService,
    ) {}

    /**
     * GET /api/auth/google/redirect
     * Devuelve la URL de autorización de Google.
     */
    public function redirect(): JsonResponse
    {
        $url = $this->googleAuthService->getRedirectUrl();

        return $this->success(['url' => $url], 'URL de autenticación con Google.');
    }

    /**
     * GET /api/auth/google/callback
     * Procesa el retorno de Google y redirige al frontend con el token.
     */
    public function callback(Request $request)
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        // Google puede devolver su propio error (ej: acceso denegado por el usuario)
        if ($request->has('error')) {
            $googleError = $request->get('error');
            Log::warning('Google OAuth: Google devolvió un error', ['error' => $googleError]);

            $tipo = $googleError === 'access_denied' ? 'access_denied' : 'google_auth_failed';
            return redirect("{$frontendUrl}/auth/google/callback?error={$tipo}");
        }

        // El code es obligatorio para completar el flujo OAuth
        if (!$request->has('code')) {
            Log::error('Google OAuth: falta el parámetro code en el callback');
            return redirect("{$frontendUrl}/auth/google/callback?error=missing_code");
        }

        try {
            $resultado   = $this->googleAuthService->handleCallback();
            $token       = $resultado['token'];

            return redirect("{$frontendUrl}/auth/google/callback?token={$token}");
        } catch (\Exception $e) {
            Log::error('Google OAuth: falló handleCallback', [
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);

            return redirect("{$frontendUrl}/auth/google/callback?error=google_auth_failed");
        }
    }
}
