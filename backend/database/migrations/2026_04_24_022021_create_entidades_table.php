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
        // 🔹 Crea la tabla 'entidades'
        Schema::create('entidades', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // ====================================================
            // 🔹 RELACIONES (FOREIGN KEYS)
            // ====================================================

            // 🔹 Relación con tipos_entidad
            // restrictOnDelete: no permite eliminar el tipo si hay entidades asociadas
            $table->foreignId('tipo_entidad_id')
                ->constrained('tipos_entidad')
                ->restrictOnDelete();

            // 🔹 Relación con lugares
            // restrictOnDelete: no permite eliminar el lugar si tiene entidades
            $table->foreignId('lugar_id')
                ->constrained('lugares')
                ->restrictOnDelete();

            // ====================================================
            // 🔹 INFORMACIÓN BÁSICA
            // ====================================================

            // 🔹 Nombre comercial (ej: Restaurante El Sabor)
            $table->string('nombre_comercial', 150);

            // 🔹 Razón social (nombre legal)
            $table->string('razon_social', 150);

            // 🔹 RUT o identificación (requerido)
            $table->string('rut', 50);

            // 🔹 Descripción de la entidad (opcional)
            $table->text('descripcion')->nullable();

            // ====================================================
            // 🔹 INFORMACIÓN DE CONTACTO
            // ====================================================

            // 🔹 Teléfono de contacto
            $table->string('telefono', 30);

            // 🔹 Dirección física
            $table->string('direccion', 255);

            // 🔹 Horario de atención
            $table->string('hora_atencion', 100);

            // 🔹 Sitio web (opcional)
            $table->string('sitio_web', 255)->nullable();

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

            // 🔹 Mejora búsquedas por tipo de entidad
            $table->index('tipo_entidad_id');

            // 🔹 Mejora filtros por estado
            $table->index('estado');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('entidades');
    }
};
