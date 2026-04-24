<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 🔹 Clase anónima para la migración
return new class extends Migration
{
    // 🔹 Se ejecuta al correr: php artisan migrate
    public function up(): void
    {
        // 🔹 Crea la tabla 'sitios_turisticos'
        Schema::create('sitios_turisticos', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'lugares'
            // restrictOnDelete: no permite eliminar el lugar si tiene sitios asociados
            $table->foreignId('lugar_id')
                ->constrained('lugares')
                ->restrictOnDelete();

            // 🔹 Nombre del sitio turístico
            $table->string('nombre', 150);

            // 🔹 Descripción del sitio
            $table->text('descripcion');

            // ====================================================
            // 🔹 IMÁGENES (REGLA DE NEGOCIO)
            // ====================================================

            // 🔹 Se requieren exactamente 3 imágenes obligatorias
            $table->string('url_imagen_1', 255);
            $table->string('url_imagen_2', 255);
            $table->string('url_imagen_3', 255);

            // ====================================================
            // 🔹 CONTROL
            // ====================================================

            // 🔹 Estado (activo/inactivo)
            $table->boolean('estado')->default(true);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Soft deletes (eliminado lógico)
            $table->softDeletes();

            // 🔹 Índice para mejorar filtros por estado
            $table->index('estado');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('sitios_turisticos');
    }
};
