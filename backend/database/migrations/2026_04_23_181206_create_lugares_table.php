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
        // 🔹 Crea la tabla 'lugares'
        Schema::create('lugares', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental (id BIGINT)
            $table->id();

            // 🔹 Nombre del lugar (máx 100 caracteres)
            $table->string('nombre', 100);

            // 🔹 Departamento al que pertenece el lugar (ej: Antioquia)
            $table->string('departamento', 100);

            // 🔹 Descripción del lugar (puede ser NULL)
            $table->text('descripcion')->nullable();

            // 🔹 Estado del lugar (activo/inactivo)
            // true = activo, false = inactivo
            $table->boolean('estado')->default(true);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Soft deletes:
            // agrega columna deleted_at para "eliminado lógico"
            // (no se borra físicamente de la BD)
            $table->softDeletes();
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('lugares');
    }
};
