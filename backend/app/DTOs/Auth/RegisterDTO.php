<?php

namespace App\DTOs\Auth;

use Illuminate\Http\Request;

// 🔹 DTO (Data Transfer Object) para el registro de usuarios
class RegisterDTO
{
    // 🔹 Constructor con propiedades readonly (inmutables)
    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $password,
    ) {}

    /**
     * 🔹 Crea el DTO a partir de un Request HTTP
     * Se usa normalmente en controladores
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // 🔹 Obtiene el nombre como string seguro, lo limpia y elimina espacios
            nombre: $request->string('nombre')->trim()->value(),

            // 🔹 Convierte el email a minúsculas y elimina espacios
            email: $request->string('email')->lower()->trim()->value(),

            // 🔹 Obtiene la contraseña tal cual (no se transforma aquí)
            password: $request->input('password'),
        );
    }

    /**
     * 🔹 Crea el DTO desde un array
     * Útil para tests, seeders o comandos
     */
    public static function fromArray(array $data): self
    {
        return new self(
            // 🔹 Limpia espacios del nombre
            nombre: trim($data['nombre']),

            // 🔹 Normaliza el email (minúsculas + trim)
            email: strtolower(trim($data['email'])),

            // 🔹 Contraseña sin modificar
            password: $data['password'],
        );
    }
}
