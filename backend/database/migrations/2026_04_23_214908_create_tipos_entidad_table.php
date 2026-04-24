<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 🔹 Clase anónima que define una migración
return new class extends Migration
{
    // 🔹 Método que se ejecuta al correr: php artisan migrate
    public function up(): void
    {
        // 🔹 Crea la tabla 'tipos_entidad'
        Schema::create('tipos_entidad', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental (id BIGINT)
            $table->id();

            // 🔹 Nombre del tipo de entidad (ej: hotel, restaurante, etc.)
            // Máximo 100 caracteres y no puede repetirse
            $table->string('nombre', 100)->unique();

            // 🔹 URL de una imagen asociada al tipo de entidad
            // Puede ser NULL si no se tiene imagen
            $table->string('url_imagen', 255)->nullable();

            // 🔹 Campos automáticos de Laravel:
            // - created_at
            // - updated_at
            $table->timestamps();
        });
    }

    // 🔹 Método que se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('tipos_entidad');
    }
};
