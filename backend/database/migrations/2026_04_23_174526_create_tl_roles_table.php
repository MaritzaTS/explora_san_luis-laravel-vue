<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 🔹 Clase anónima que define una migración
return new class extends Migration
{
    // 🔹 Se ejecuta al correr: php artisan migrate
    public function up(): void
    {
        // 🔹 Crea la tabla 'roles'
        Schema::create('roles', function (Blueprint $table) {

            // 🔹 Crea una columna 'id' autoincremental (BIGINT) como llave primaria
            $table->id();

            // 🔹 Nombre del rol (ej: admin, user, etc.)
            // Máximo 50 caracteres y debe ser único (no se repiten)
            $table->string('nombre', 50)->unique();

            // 🔹 Crea automáticamente:
            // - created_at
            // - updated_at
            $table->timestamps();
        });
    }

    // 🔹 Se ejecuta al hacer rollback: php artisan migrate:rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('roles');
    }
};
