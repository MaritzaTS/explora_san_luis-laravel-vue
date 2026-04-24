<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

// 🔹 Factory para generar datos falsos del modelo Usuario
class UsuarioFactory extends Factory
{
    // 🔹 Indica a qué modelo pertenece esta factory
    protected $model = Usuario::class;

    // 🔹 Define los datos por defecto para crear usuarios fake
    public function definition(): array
    {
        return [

            // 🔹 Nombre aleatorio usando Faker
            'nombre' => fake()->name(),

            // 🔹 Email único y válido generado automáticamente
            'email' => fake()->unique()->safeEmail(),

            // 🔹 Contraseña encriptada
            // Siempre será "password" pero hasheada
            'contrasena' => Hash::make('password'),

            // 🔹 ID del rol (por defecto 2, por ejemplo: usuario normal)
            'rol_id' => 2,

            // 🔹 Usuario verificado por defecto
            'verificado' => true,

            // 🔹 Usuario activo por defecto
            'estado' => true,
        ];
    }
}
