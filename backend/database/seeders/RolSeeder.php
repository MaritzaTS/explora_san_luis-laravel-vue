<?php

namespace Database\Seeders;

use App\Enums\RolEnum;
use App\Models\Rol;
use Illuminate\Database\Seeder;

// 🔹 Seeder que usa el Enum para poblar la tabla de roles
class RolSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        // 🔹 Recorre todos los roles definidos en el enum
        foreach (RolEnum::all() as $rol) {

            Rol::updateOrCreate(
                // 🔹 Busca por ID (si existe lo actualiza)
                ['id' => $rol['id']],

                // 🔹 Si no existe lo crea con este nombre
                ['nombre' => $rol['nombre']]
            );
        }
    }
}
