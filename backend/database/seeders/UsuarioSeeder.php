<?php

namespace Database\Seeders;

use App\Enums\RolEnum;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// 🔹 Seeder para crear un usuario administrador por defecto
class UsuarioSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        Usuario::updateOrCreate(
            [
                // 🔹 Condición de búsqueda:
                // si ya existe este email, actualiza el registro
                'email' => 'admin@admin.com'
            ],
            [
                // 🔹 Datos a insertar o actualizar

                // Nombre del usuario
                'nombre' => 'Administrador',

                // 🔹 Contraseña encriptada
                'contrasena' => Hash::make('123456'),

                // 🔹 Rol usando Enum (más seguro que hardcodear números)
                'rol_id' => RolEnum::ADMIN->value,

                // 🔹 Usuario verificado por defecto
                'verificado' => true,

                // 🔹 Usuario activo
                'estado' => true,
            ]
        );
    }
}
