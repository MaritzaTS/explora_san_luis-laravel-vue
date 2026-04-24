<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// 🔹 Modelo TipoEspecifico que representa la tabla 'tipos_especificos'
class TipoEspecifico extends Model
{
    // 🔹 Nombre de la tabla en la base de datos
    protected $table = 'tipos_especificos';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'tipo_entidad_id',
        'nombre',
    ];

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Relación: Un subtipo pertenece a un tipo de entidad
     * Ej: "restaurante" → "gastronomía"
     */
    public function tipoEntidad(): BelongsTo
    {
        // Laravel asume:
        // - foreign key: tipo_entidad_id
        // - owner key: id en tipos_entidad
        return $this->belongsTo(TipoEntidad::class);
    }

    /**
     * 🔹 Relación: Un subtipo puede estar asignado a muchas entidades
     * Ej: "restaurante" → [El Sabor, La Plaza, El Rincón, ...]
     *
     * 🔹 Relación many-to-many usando tabla pivote
     */
    public function entidades(): BelongsToMany
    {
        return $this->belongsToMany(
            Entidad::class,              // 🔹 Modelo relacionado
            'entidad_tipo_especifico',   // 🔹 Tabla pivote
            'tipo_especifico_id',        // 🔹 FK de este modelo en la pivote
            'entidad_id'                 // 🔹 FK del otro modelo en la pivote
        );
    }
}
