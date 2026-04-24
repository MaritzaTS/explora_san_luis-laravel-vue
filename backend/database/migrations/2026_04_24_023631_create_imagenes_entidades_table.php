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
        // 🔹 Crea la tabla 'imagenes_entidades'
        Schema::create('imagenes_entidades', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'entidades'
            // cascadeOnDelete: si se elimina la entidad, se eliminan sus imágenes
            $table->foreignId('entidad_id')
                ->constrained('entidades')
                ->cascadeOnDelete();

            // 🔹 URL de la imagen (ruta o link)
            $table->string('url_imagen', 255);

            // 🔹 Descripción de la imagen (opcional)
            $table->string('descripcion', 150)->nullable();

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Índice para mejorar consultas por entidad
            $table->index('entidad_id');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('imagenes_entidades');
    }
};
