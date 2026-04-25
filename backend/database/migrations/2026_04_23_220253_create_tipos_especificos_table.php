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
        // 🔹 Crea la tabla 'tipos_especificos'
        Schema::create('tipos_especificos', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'tipos_entidad'
            // tipo_entidad_id referencia a id en tipos_entidad
            // cascadeOnDelete: si se elimina el tipo padre, se eliminan sus subtipos
            $table->foreignId('tipo_entidad_id')
                ->constrained('tipos_entidad')
                ->cascadeOnDelete();

            // 🔹 Nombre del subtipo (ej: restaurante, café, etc.)
            $table->string('nombre', 100);

            $table->string('slug', 120);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Restricción única compuesta:
            // Evita duplicados del mismo nombre dentro de un mismo tipo
            // Ej: no puede haber dos "restaurante" en "gastronomía"
            $table->unique(['tipo_entidad_id', 'slug']);
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('tipos_especificos');
    }
};
