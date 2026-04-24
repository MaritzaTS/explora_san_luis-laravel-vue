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
        // 🔹 Crea la tabla 'eventos'
        Schema::create('eventos', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'lugares'
            // restrictOnDelete: no permite eliminar el lugar si tiene eventos
            $table->foreignId('lugar_id')
                  ->constrained('lugares')
                  ->restrictOnDelete();

            // ====================================================
            // 🔹 INFORMACIÓN BÁSICA
            // ====================================================

            // 🔹 Nombre del evento
            $table->string('nombre', 150);

            // 🔹 Descripción del evento (opcional)
            $table->text('descripcion')->nullable();

            // 🔹 Imagen principal o poster del evento
            $table->string('url_poster', 255)->nullable();

            // ====================================================
            // 🔹 FECHAS
            // ====================================================

            // 🔹 Fecha de inicio del evento
            $table->date('fecha_inicio');

            // 🔹 Fecha de finalización del evento
            $table->date('fecha_fin');

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

            // ====================================================
            // 🔹 ÍNDICES
            // ====================================================

            // 🔹 Mejora consultas ordenadas por fecha de inicio
            $table->index('fecha_inicio');

            // 🔹 Mejora filtros por estado
            $table->index('estado');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('eventos');
    }
};
