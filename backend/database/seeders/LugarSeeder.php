<?php

namespace Database\Seeders;

use App\Models\Lugar;
use Illuminate\Database\Seeder;

// 🔹 Seeder para poblar la tabla 'lugares'
class LugarSeeder extends Seeder
{
    // 🔹 Método que se ejecuta con: php artisan db:seed
    public function run(): void
    {
        // 🔹 Lista de lugares a insertar
        $lugares = [
            [
                'nombre'       => 'San Luis',
                'departamento' => 'Antioquia',
                'descripcion'  => 'Municipio del oriente antioqueño, conocido por sus cascadas, ríos cristalinos y biodiversidad.',
                'estado'       => true,
            ],
        ];

        // 🔹 Recorre cada lugar y lo crea o actualiza
        foreach ($lugares as $lugar) {

            Lugar::updateOrCreate(
                [
                    // 🔹 Condición para evitar duplicados:
                    // combina nombre + departamento
                    'nombre'       => $lugar['nombre'],
                    'departamento' => $lugar['departamento'],
                ],
                // 🔹 Datos a insertar o actualizar
                $lugar
            );
        }
    }
}
