<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// 🔹 Modelo ImagenEntidad que representa la tabla 'imagenes_entidades'
class ImagenEntidad extends Model
{
    // 🔹 Nombre de la tabla en la base de datos
    protected $table = 'imagenes_entidades';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'entidad_id',
        'url_imagen',
        'descripcion',
    ];

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Una imagen pertenece a una entidad
     */
    public function entidad(): BelongsTo
    {
        // Laravel asume:
        // - foreign key: entidad_id
        // - owner key: id en entidades
        return $this->belongsTo(Entidad::class);
    }

    // ====================================================
    // 🔹 ACCESSORS
    // ====================================================

    /**
     * 🔹 Genera la URL completa de la imagen para el frontend
     * Uso: $imagen->url_completa
     */
    public function getUrlCompletaAttribute(): string
    {
        // 🔹 asset() genera la URL pública (ej: http://localhost/storage/...)
        return asset('storage/' . $this->url_imagen);
    }
}
