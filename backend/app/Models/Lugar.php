<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// 🔹 Modelo Lugar que representa la tabla 'lugares'
class Lugar extends Model
{
    // 🔹 Trait para habilitar borrado lógico (soft delete)
    use SoftDeletes;

    // 🔹 Nombre de la tabla en la base de datos
    protected $table = 'lugares';

    // 🔹 Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'departamento',
        'descripcion',
        'estado',
    ];

    // 🔹 Casts: convierte automáticamente tipos de datos
    protected $casts = [
        'estado' => 'boolean', // asegura que estado siempre sea true/false
    ];

    /**
     * 🔹 Relación: Un lugar tiene muchas entidades
     */
    public function entidades(): HasMany
    {
        // Laravel asume:
        // - foreign key: lugar_id en la tabla entidades
        return $this->hasMany(Entidad::class);
    }

    /**
     * 🔹 Relación: Un lugar tiene muchos sitios turísticos
     */
    public function sitiosTuristicos(): HasMany
    {
        // foreign key por convención: lugar_id
        return $this->hasMany(SitioTuristico::class);
    }

    /**
     * 🔹 Relación: Un lugar tiene muchos eventos
     */
    public function eventos(): HasMany
    {
        // foreign key por convención: lugar_id
        return $this->hasMany(Evento::class);
    }

    /**
     * 🔹 Scope: filtra solo lugares activos
     * Uso: Lugar::activos()->get();
     */
    public function scopeActivos($query)
    {
        // 🔹 Solo retorna registros donde estado = true
        return $query->where('estado', true);
    }
}
