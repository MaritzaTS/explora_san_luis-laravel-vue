<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// 🔹 Modelo Entidad que representa la tabla 'entidades'
class Entidad extends Model
{
    // 🔹 Trait para borrado lógico
    use SoftDeletes;

    // 🔹 Nombre de la tabla
    protected $table = 'entidades';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'tipo_entidad_id',
        'lugar_id',
        'nombre_comercial',
        'razon_social',
        'rut',
        'descripcion',
        'telefono',
        'direccion',
        'hora_atencion',
        'sitio_web',
        'estado',
    ];

    // 🔹 Casts automáticos
    protected $casts = [
        'estado' => 'boolean',
    ];

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Una entidad pertenece a un tipo (gastronomía, recreación, etc.)
     */
    public function tipoEntidad(): BelongsTo
    {
        // foreign key: tipo_entidad_id
        return $this->belongsTo(TipoEntidad::class);
    }

    /**
     * 🔹 Una entidad pertenece a un lugar (San Luis, etc.)
     */
    public function lugar(): BelongsTo
    {
        // foreign key: lugar_id
        return $this->belongsTo(Lugar::class);
    }

    /**
     * 🔹 Una entidad puede tener múltiples subtipos (relación many-to-many)
     * Ej: restaurante + café_bar
     */
    public function tiposEspecificos(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoEspecifico::class,      // 🔹 Modelo relacionado
            'entidad_tipo_especifico',  // 🔹 Tabla pivote
            'entidad_id',               // 🔹 FK de esta tabla en la pivote
            'tipo_especifico_id'        // 🔹 FK del otro modelo
        );
    }

    /**
     * 🔹 Una entidad tiene múltiples imágenes (logos, fotos, etc.)
     */
    public function imagenes(): HasMany
    {
        // foreign key por convención: entidad_id
        return $this->hasMany(ImagenEntidad::class);
    }

    // ====================================================
    // 🔹 SCOPES (FILTROS REUTILIZABLES)
    // ====================================================

    /**
     * 🔹 Filtra solo entidades activas
     * Uso: Entidad::activas()->get();
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /**
     * 🔹 Filtra entidades por tipo (gastronomía, alojamiento, etc.)
     */
    public function scopeDelTipo($query, int $tipoEntidadId)
    {
        return $query->where('tipo_entidad_id', $tipoEntidadId);
    }

    /**
     * 🔹 Filtra entidades que tengan alguno de los subtipos dados
     * Usa whereHas para filtrar por relación many-to-many
     *
     * @param array<int> $subtiposIds
     */
    public function scopeConSubtipos($query, array $subtiposIds)
    {
        // 🔹 Si no hay filtros, retorna la query sin modificar
        if (empty($subtiposIds)) {
            return $query;
        }

        // 🔹 Filtra entidades que tengan subtipos en la lista
        return $query->whereHas('tiposEspecificos', function ($q) use ($subtiposIds) {
            $q->whereIn('tipos_especificos.id', $subtiposIds);
        });
    }
}
