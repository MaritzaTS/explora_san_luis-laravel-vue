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
        // 🔹 Crea la tabla pivote 'entidad_tipo_especifico'
        Schema::create('entidad_tipo_especifico', function (Blueprint $table) {

            // 🔹 Llave foránea hacia 'entidades'
            // cascadeOnDelete: si se elimina la entidad, se eliminan sus relaciones
            $table->foreignId('entidad_id')
                ->constrained('entidades')
                ->cascadeOnDelete();

            // 🔹 Llave foránea hacia 'tipos_especificos'
            // cascadeOnDelete: si se elimina el subtipo, se eliminan sus relaciones
            $table->foreignId('tipo_especifico_id')
                ->constrained('tipos_especificos')
                ->cascadeOnDelete();

            // 🔹 Clave primaria compuesta:
            // Evita duplicados de la misma combinación entidad + subtipo
            // Ej: no puede existir dos veces (entidad 1, subtipo restaurante)
            $table->primary(['entidad_id', 'tipo_especifico_id']);
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('entidad_tipo_especifico');
    }
};
