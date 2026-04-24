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
        // 🔹 Crea la tabla 'usuarios'
        Schema::create('usuarios', function (Blueprint $table) {

            // 🔹 Llave primaria autoincremental
            $table->id();

            // 🔹 Nombre del usuario
            $table->string('nombre', 150);

            // 🔹 Email único del usuario
            $table->string('email', 150)->unique();

            // 🔹 Contraseña (puede ser NULL si el usuario inicia con Google)
            $table->string('contrasena')->nullable();

            // 🔹 ID de Google para autenticación OAuth
            // Es único pero opcional
            $table->string('id_google', 100)->unique()->nullable();

            // 🔹 Indica si el usuario está verificado
            $table->boolean('verificado')->default(false);

            // 🔹 Relación con la tabla roles (foreign key)
            // rol_id referencia a id en tabla roles
            // restrictOnDelete evita eliminar el rol si tiene usuarios
            $table->foreignId('rol_id')->constrained('roles')->restrictOnDelete();

            // 🔹 Estado del usuario (activo/inactivo)
            $table->boolean('estado')->default(true);

            // 🔹 Código de verificación (ej: para email)
            $table->string('codigo_verificacion', 6)->nullable();

            // 🔹 Fecha y hora de expiración del código
            $table->timestamp('codigo_expira_en')->nullable();

            // 🔹 Número de intentos para ingresar el código
            $table->unsignedTinyInteger('intentos_codigo')->default(0);

            // 🔹 Refresh token para autenticación (ej: JWT refresh)
            $table->text('refresh_token')->nullable();

            // 🔹 Versión del token (útil para invalidar tokens antiguos)
            $table->unsignedInteger('token_version')->default(1);

            // 🔹 Campos automáticos:
            // - created_at
            // - updated_at
            $table->timestamps();

            // 🔹 Soft deletes (eliminado lógico)
            $table->softDeletes();

            // 🔹 Índice adicional para mejorar búsquedas por email
            $table->index('email');
        });
    }

    // 🔹 Se ejecuta al hacer rollback
    public function down(): void
    {
        // 🔹 Elimina la tabla si existe
        Schema::dropIfExists('usuarios');
    }
};
