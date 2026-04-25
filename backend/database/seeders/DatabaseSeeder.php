<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// 🔹 Seeder principal que orquesta la ejecución de todos los seeders
class DatabaseSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        $this->call([

            // ====================================================
            // 🔹 1. DATOS BASE (SIN DEPENDENCIAS)
            // ====================================================

            // 🔹 Roles del sistema (admin, usuario)
            RolSeeder::class,

            // 🔹 Lugares (ej: San Luis)
            LugarSeeder::class,

            // 🔹 Tipos de entidad (gastronomía, alojamiento, etc.)
            TipoEntidadSeeder::class,

            // ====================================================
            // 🔹 2. DATOS DEPENDIENTES
            // ====================================================

            // 🔹 Usuario admin (depende de roles)
            UsuarioSeeder::class,        // necesita RolSeeder

            // 🔹 Subtipos (depende de tipos_entidad)
            TipoEspecificoSeeder::class, // necesita TipoEntidadSeeder
        ]);
    }
}
