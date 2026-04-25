<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// 🔹 Modelo TipoEntidad que representa la tabla 'tipos_entidad'
class TipoEntidad extends Model
{
    // 🔹 Nombre de la tabla en la base de datos
    protected $table = 'tipos_entidad';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'slug',
        'url_imagen',
    ];

    /**
     * Para que las URLs busquen por slug en lugar de id.
     * Permite hacer Route::get('/tipos/{tipoEntidad:slug}', ...)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-genera el slug cuando se crea o actualiza el nombre
     * y no se pasó un slug manual.
     */
    protected static function booted(): void
    {
        static::saving(function (TipoEntidad $tipo) {
            if (empty($tipo->slug) && !empty($tipo->nombre)) {
                $tipo->slug = Str::slug($tipo->nombre);
            }
        });
    }

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Relación: Un tipo de entidad tiene muchos subtipos
     * Ej: "gastronomía" → [restaurante, café, comidas rápidas]
     */
    public function tiposEspecificos(): HasMany
    {
        // Laravel asume:
        // - foreign key: tipo_entidad_id en la tabla tipos_especificos
        return $this->hasMany(TipoEspecifico::class);
    }

    /**
     * 🔹 Relación: Un tipo de entidad tiene muchas entidades registradas
     * Ej: "gastronomía" → [Restaurante El Sabor, Café La Plaza, ...]
     */
    public function entidades(): HasMany
    {
        // foreign key por convención: tipo_entidad_id
        return $this->hasMany(Entidad::class);
    }
}
