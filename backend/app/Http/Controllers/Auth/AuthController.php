<?php

namespace App\Http\Controllers\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\DTOs\Auth\VerificarCodigoDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerificarCodigoRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

// 🔹 Controlador de autenticación
class AuthController extends Controller
{
    use ApiResponse;
    // 🔹 Inyección del servicio de autenticación
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * 🔹 Endpoint: POST /api/auth/register
     *
     * Flujo completo:
     * 1. RegisterRequest valida datos
     * 2. DTO encapsula la información
     * 3. Service ejecuta la lógica de negocio
     * 4. Resource formatea la respuesta
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // 🔹 Convierte el request va3lidado en DTO
        $dto = RegisterDTO::fromRequest($request);

        // 🔹 Ejecuta la lógica de registro
        $usuario = $this->authService->register($dto);


        return $this->created(
            new UsuarioResource($usuario),
            'Usuario registrado exitosamente. Revisa tu correo para verificar tu cuenta.'
        );
    }

    /**
     * POST /api/auth/verificar-codigo
     */
    public function verificarCodigo(VerificarCodigoRequest $request): JsonResponse
    {
        $dto = VerificarCodigoDTO::fromRequest($request);

        $resultado = $this->authService->verificarCodigo($dto);

        return $this->success(
            [
                'usuario' => new UsuarioResource($resultado['usuario']),
                'token'   => $resultado['token'],
            ],
            'Cuenta verificada exitosamente.'
        );
    }
}
