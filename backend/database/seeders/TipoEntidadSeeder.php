<?php

namespace Database\Seeders;

use App\Models\TipoEntidad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// 🔹 Seeder para poblar la tabla 'tipos_entidad'
class TipoEntidadSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        // 🔹 Lista de tipos de entidad
        $tipos = [
            'Gastronomía',
            'Recreación',
            'Alojamiento',
            'Transporte',
            'Agencias Turísticas',
        ];

        // 🔹 Recorre cada tipo y lo crea o actualiza
        foreach ($tipos as $nombre) {

            TipoEntidad::updateOrCreate(
                [
                    // 🔹 Condición de búsqueda:
                    // usa el slug (versión URL-friendly del nombre)
                    'slug' => Str::slug($nombre),
                ],
                [
                    // 🔹 Datos a insertar o actualizar
                    'nombre' => $nombre,

                    // 🔹 Genera un slug (ej: "Agencias Turísticas" → "agencias-turisticas")
                    'slug'   => Str::slug($nombre),
                ]
            );
        }
    }
}
