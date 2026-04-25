<?php

namespace App\Enums;

// 🔹 Enum de roles del sistema (tipado fuerte en PHP 8.1+)
enum RolEnum: int
{
    // 🔹 Casos del enum con su valor entero (coinciden con la BD)
    case ADMIN = 1;
    case USUARIO = 2;

    /**
     * 🔹 Retorna el nombre legible del rol
     * (útil para guardar en BD o mostrar en frontend)
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN   => 'admin',
            self::USUARIO => 'usuario',
        };
    }

    /**
     * 🔹 Devuelve todos los roles como array estructurado
     * Formato: [ ['id' => 1, 'nombre' => 'admin'], ... ]
     *
     * 🔹 Útil para seeders o listas sin duplicar lógica
     */
    public static function all(): array
    {
        return array_map(
            // 🔹 Convierte cada caso del enum en array
            fn(self $case) => [
                'id' => $case->value,      // valor entero
                'nombre' => $case->label() // nombre legible
            ],
            self::cases() // 🔹 Retorna todos los casos del enum
        );
    }
}
