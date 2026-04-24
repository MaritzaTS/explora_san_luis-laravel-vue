<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// 🔹 Modelo SitioTuristico que representa la tabla 'sitios_turisticos'
class SitioTuristico extends Model
{
    // 🔹 Trait para borrado lógico
    use SoftDeletes;

    // 🔹 Nombre de la tabla
    protected $table = 'sitios_turisticos';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'lugar_id',
        'nombre',
        'descripcion',
        'url_imagen_1',
        'url_imagen_2',
        'url_imagen_3',
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
     * 🔹 Un sitio turístico pertenece a un lugar
     */
    public function lugar(): BelongsTo
    {
        // Laravel asume:
        // - foreign key: lugar_id
        // - owner key: id en lugares
        return $this->belongsTo(Lugar::class);
    }

    // ====================================================
    // 🔹 ACCESSORS
    // ====================================================

    /**
     * 🔹 Retorna un array con las URLs completas de las 3 imágenes
     * Uso: $sitio->imagenes_completas
     */
    public function getImagenesCompletasAttribute(): array
    {
        return [
            asset('storage/' . $this->url_imagen_1),
            asset('storage/' . $this->url_imagen_2),
            asset('storage/' . $this->url_imagen_3),
        ];
    }

    // ====================================================
    // 🔹 SCOPES
    // ====================================================

    /**
     * 🔹 Filtra solo sitios activos
     * Uso: SitioTuristico::activos()->get();
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
