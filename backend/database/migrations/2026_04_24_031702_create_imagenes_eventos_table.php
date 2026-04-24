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
        // 🔹 Crea la tabla 'imagenes_eventos'
        Schema::create('imagenes_eventos', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'eventos'
            // cascadeOnDelete: si se elimina el evento, se eliminan sus imágenes
            $table->foreignId('evento_id')
                ->constrained('eventos')
                ->cascadeOnDelete();

            // 🔹 URL de la imagen (ruta o link)
            $table->string('url_imagen', 255);

            // 🔹 Descripción de la imagen (opcional)
            $table->string('descripcion', 150)->nullable();

            // 🔹 Orden de la imagen dentro de la galería
            // útil para mostrar imágenes en secuencia
            $table->unsignedSmallInteger('orden')->default(0);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Índice para mejorar consultas por evento
            $table->index('evento_id');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('imagenes_eventos');
    }
};
