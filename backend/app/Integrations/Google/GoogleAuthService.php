<?php

namespace App\Integrations\Google;

use App\Enums\RolEnum;
use App\Models\Usuario;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use App\Services\Auth\JwtService;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

/**
 * Servicio para gestionar la autenticación mediante Google OAuth2.
 * Integra Laravel Socialite con el sistema de persistencia y emisión de tokens JWT.
 */
class GoogleAuthService
{
    /**
     * Constructor con Inyección de Dependencias.
     * * @param UsuarioRepositoryInterface $usuarioRepository Repositorio para operaciones de base de datos de usuarios.
     * @param JwtService $jwtService Servicio para la generación de tokens de acceso.
     */
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository,
        private readonly JwtService $jwtService,
    ) {}

    /**
     * Genera la URL de redirección hacia el servidor de autorización de Google.
     * * @return string URL completa del endpoint de Google.
     */
    public function getRedirectUrl(): string
    {
        // stateless() desactiva la verificación de estado de sesión (útil para APIs)
        return Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Procesa la respuesta (callback) enviada por Google tras la autorización del usuario.
     * Implementa la lógica de "Sincronización o Registro":
     * 1. Obtiene los datos del perfil de Google.
     * 2. Verifica si el email ya existe en el sistema.
     * 3. Vincula la cuenta o crea un nuevo registro verificado.
     * * @return array Datos del usuario y su token JWT.
     */
    public function handleCallback(): array
    {
        // Recupera los datos del usuario autenticado en Google
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        // Intenta localizar al usuario por su correo electrónico principal
        $usuario = $this->usuarioRepository->findByEmail($googleUser->getEmail());

        if ($usuario) {
            // Escenario: El usuario ya existe pero quizás no se había autenticado con Google antes
            if (empty($usuario->id_google)) {
                $this->usuarioRepository->update($usuario, [
                    'id_google'  => $googleUser->getId(),
                    'verificado' => true, // Al venir de Google, el email se marca como verificado
                ]);
                // Refresca el modelo para obtener los datos actualizados de la DB
                $usuario = $usuario->fresh();
            }
        } else {
            // Escenario: El usuario no existe, se procede a crear un registro nuevo
            $usuario = $this->usuarioRepository->create([
                'nombre'     => $googleUser->getName(),
                'email'      => $googleUser->getEmail(),
                'id_google'  => $googleUser->getId(),
                'verificado' => true,
                'estado'     => true,
                'rol_id'     => RolEnum::USUARIO->value, // Asigna el rol por defecto
            ]);
        }

        // Genera la sesión mediante un token JWT basado en el usuario (existente o nuevo)
        $token = $this->jwtService->generarToken($usuario);

        return [
            'usuario' => $usuario,
            'token'   => $token,
        ];
    }
}
