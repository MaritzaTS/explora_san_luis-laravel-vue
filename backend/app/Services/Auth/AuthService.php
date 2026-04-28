<?php

namespace App\Services\Auth;

use App\DTOs\Auth\RegisterDTO;
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
     * 🔹 Genera un código numérico de 6 dígitos
     * Ej: 004821
     */
    private function generarCodigoVerificacion(): string
    {
        // 🔹 random_int es criptográficamente seguro
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
