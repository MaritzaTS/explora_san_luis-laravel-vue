<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\DTOs\Auth\VerificarCodigoDTO;
use App\Enums\RolEnum;
use App\Events\Auth\UsuarioRegistrado;
use App\Exceptions\AuthException;
use App\Models\Usuario;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use Illuminate\Support\Facades\Hash;

// 🔹 Servicio encargado de la lógica de autenticación (registro en este caso)
class AuthService
{
    // 🔹 Inyección de dependencias (Repository)
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository,
        private readonly JwtService $jwtService,
    ) {}

    /**
     * 🔹 Registra un nuevo usuario en el sistema
     *
     * Flujo:
     * 1. Validar email único
     * 2. Generar código de verificación
     * 3. Crear usuario en BD
     * 4. Disparar evento (envío de email)
     * 5. Retornar usuario
     */
    public function register(RegisterDTO $dto): Usuario
    {
        // ====================================================
        // 1. VALIDAR EMAIL ÚNICO
        // ====================================================
        if ($this->usuarioRepository->existsByEmail($dto->email)) {
            throw AuthException::emailYaRegistrado($dto->email);
        }

        // ====================================================
        // 2. GENERAR CÓDIGO DE VERIFICACIÓN
        // ====================================================
        $codigo = $this->generarCodigoVerificacion();

        // ====================================================
        // 3. CREAR USUARIO
        // ====================================================
        $usuario = $this->usuarioRepository->create([
            'nombre'              => $dto->nombre,
            'email'               => $dto->email,

            // 🔹 Se encripta la contraseña antes de guardar
            'contrasena'          => Hash::make($dto->password),

            // 🔹 Rol por defecto: usuario
            'rol_id'              => \App\Enums\RolEnum::USUARIO->value,

            // 🔹 Usuario no verificado inicialmente
            'verificado'          => false,

            // 🔹 Usuario activo
            'estado'              => true,

            // 🔹 Código de verificación y expiración
            'codigo_verificacion' => $codigo,
            'codigo_expira_en'    => now()->addMinutes(30),

            // 🔹 Intentos iniciales
            'intentos_codigo'     => 0,
        ]);

        // ====================================================
        // 4. DISPARAR EVENTO
        // ====================================================
        // 🔹 El listener se encarga de enviar el email
        event(new UsuarioRegistrado($usuario, $codigo));

        // ====================================================
        // 5. RETORNAR USUARIO
        // ====================================================
        return $usuario;
    }

    /**
     * Verifica el código de 6 dígitos y activa la cuenta.
     *
     * Flujo:
     * 1. Buscar usuario por email
     * 2. Validar que no esté ya verificado
     * 3. Validar intentos (máximo 3)
     * 4. Validar expiración (30 minutos)
     * 5. Validar que el código coincida
     * 6. Marcar como verificado
     * 7. Generar token JWT
     */
    public function verificarCodigo(VerificarCodigoDTO $dto): array
    {
        // 1. Buscar usuario
        $usuario = $this->usuarioRepository->findByEmail($dto->email);

        if (!$usuario) {
            throw AuthException::credencialesInvalidas();
        }

        // 2. Ya verificado
        if ($usuario->verificado) {
            throw AuthException::emailYaRegistrado($dto->email);
        }

        // 3. Máximo 3 intentos
        if ($usuario->intentos_codigo >= 3) {
            throw AuthException::demasiadosIntentos();
        }

        // 4. Código expirado
        if ($usuario->codigo_expira_en && $usuario->codigo_expira_en->isPast()) {
            throw AuthException::codigoExpirado();
        }

        // 5. Código incorrecto
        if ($usuario->codigo_verificacion !== $dto->codigo) {
            $this->usuarioRepository->update($usuario, [
                'intentos_codigo' => $usuario->intentos_codigo + 1,
            ]);

            throw AuthException::codigoIncorrecto();
        }

        // 6. Verificar cuenta
        $usuario = $this->usuarioRepository->marcarComoVerificado($usuario);

        // 7. Generar token
        $token = $this->jwtService->generarToken($usuario);

        return [
            'usuario' => $usuario,
            'token'   => $token,
        ];
    }

    /**
     * Inicia sesión con email y contraseña.
     *
     * Flujo:
     * 1. Buscar usuario por email
     * 2. Validar que esté verificado
     * 3. Validar que esté activo
     * 4. Validar contraseña
     * 5. Generar token JWT
     */
    public function login(LoginDTO $dto): array
    {
        // 1. Buscar usuario
        $usuario = $this->usuarioRepository->findByEmail($dto->email);

        if (!$usuario) {
            throw AuthException::credencialesInvalidas();
        }

        // 2. Verificado
        if (!$usuario->verificado) {
            throw AuthException::usuarioNoVerificado();
        }

        // 3. Activo
        if (!$usuario->estado) {
            throw AuthException::usuarioInactivo();
        }

        // 4. Contraseña
        if (!Hash::check($dto->password, $usuario->contrasena)) {
            throw AuthException::credencialesInvalidas();
        }

        // 5. Token
        $token = $this->jwtService->generarToken($usuario);

        return [
            'usuario' => $usuario,
            'token'   => $token,
        ];
    }

    /**
     * 🔹 Genera un código numérico de 6 dígitos
     * Ej: 004821
     */
    private function generarCodigoVerificacion(): string
    {
        // 🔹 random_int es criptográficamente seguro
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
