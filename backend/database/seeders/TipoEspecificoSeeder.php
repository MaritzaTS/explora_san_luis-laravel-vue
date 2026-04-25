<?php

namespace Database\Seeders;

use App\Models\TipoEntidad;
use App\Models\TipoEspecifico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// 🔹 Seeder para poblar la tabla 'tipos_especificos'
class TipoEspecificoSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        // 🔹 Estructura de datos:
        // clave = slug del tipo padre
        // valor = lista de subtipos
        $estructura = [
            'gastronomia' => [
                'Restaurante',
                'Restaurante Bar',
                'Comidas Rápidas',
                'Café Bar',
            ],
            'recreacion' => [
                'Piscina',
                'Finca Recreativa',
            ],
            'alojamiento' => [
                'Hotel',
                'Hostal',
                'Glamping',
                'Finca Hotel',
                'Casa Amoblada',
            ],
            'transporte' => [
                'Público',
                'Privado',
            ],
            // 🔹 "Agencias turísticas" no tiene subtipos por ahora
        ];

        // 🔹 Recorre cada tipo padre
        foreach ($estructura as $slugTipoPadre => $nombresSubtipos) {

            // 🔹 Busca el tipo padre por slug (más seguro que usar ID)
            $tipoPadre = TipoEntidad::where('slug', $slugTipoPadre)->first();

            // 🔹 Si no existe el tipo padre, muestra advertencia y continúa
            if (!$tipoPadre) {
                $this->command->warn("Tipo padre no encontrado: {$slugTipoPadre}");
                continue;
            }

            // 🔹 Recorre cada subtipo
            foreach ($nombresSubtipos as $nombre) {

                TipoEspecifico::updateOrCreate(
                    [
                        // 🔹 Condición de búsqueda
                        'tipo_entidad_id' => $tipoPadre->id,
                        'slug'            => Str::slug($nombre),
                    ],
                    [
                        // 🔹 Datos a insertar o actualizar
                        'nombre' => $nombre,
                    ]
                );
            }
        }
    }
}
