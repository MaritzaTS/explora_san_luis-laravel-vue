<?php

namespace App\Models;

use App\Enums\RolEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

// 🔹 Modelo Usuario que extiende Authenticatable (necesario para login en Laravel)
// e implementa JWTSubject para trabajar con JWT (tymon/jwt-auth)
class Usuario extends Authenticatable implements JWTSubject
{
    // 🔹 Traits:
    // - HasFactory: permite usar factories para testing/seeding
    // - Notifiable: permite enviar notificaciones (emails, etc.)
    // - SoftDeletes: borrado lógico
    use HasFactory, Notifiable, SoftDeletes;

    // 🔹 Nombre de la tabla
    protected $table = 'usuarios';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'email',
        'contrasena',
        'id_google',
        'verificado',
        'rol_id',
        'estado',
        'codigo_verificacion',
        'codigo_expira_en',
        'intentos_codigo',
        'refresh_token',
        'token_version',
    ];

    // 🔹 Campos ocultos en respuestas JSON
    protected $hidden = [
        'contrasena',
        'codigo_verificacion',
        'refresh_token',
        'remember_token',
    ];

    // 🔹 Casts automáticos de tipos
    protected $casts = [
        'verificado'       => 'boolean',
        'estado'           => 'boolean',
        'codigo_expira_en' => 'datetime',
    ];

    /**
     * 🔹 Laravel usa 'password' por defecto.
     * Aquí indicamos que la contraseña real es 'contrasena'.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Un usuario pertenece a un rol
     */
    public function rol(): BelongsTo
    {
        // Laravel asume:
        // - foreign key: rol_id
        // - owner key: id en roles
        return $this->belongsTo(Rol::class);
    }

    /**
     * 🔹 Un usuario tiene muchas reseñas
     */
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class);
    }

    // ====================================================
    // 🔹 JWT (tymon/jwt-auth)
    // ====================================================

    /**
     * 🔹 Retorna el identificador único del usuario (ID)
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 🔹 Claims personalizados que irán dentro del token JWT
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'nombre' => $this->nombre,
            'email'  => $this->email,
            'rol_id' => $this->rol_id,
        ];
    }

    // ====================================================
    // 🔹 HELPERS
    // ====================================================

    /**
     * 🔹 Verifica si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->rol_id === RolEnum::ADMIN->value;
    }

    /**
     * 🔹 Scope: usuarios activos
     * Uso: Usuario::activos()->get();
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * 🔹 Scope: usuarios verificados
     * Uso: Usuario::verificados()->get();
     */
    public function scopeVerificados($query)
    {
        return $query->where('verificado', true);
    }
}
