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
        // 🔹 Crea la tabla 'resenas'
        Schema::create('resenas', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Llave foránea hacia 'usuarios'
            // cascadeOnDelete: si se elimina el usuario, se eliminan sus reseñas
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            // 🔹 Comentario de la reseña
            $table->text('comentario');

            

            // 🔹 Estado (activa/inactiva)
            $table->boolean('estado')->default(true);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Soft deletes (eliminado lógico)
            $table->softDeletes();

            // 🔹 Índice para mejorar consultas por usuario
            $table->index('usuario_id');

            // 🔹 Índice para mejorar filtros por estado
            $table->index('estado');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('resenas');
    }
};
