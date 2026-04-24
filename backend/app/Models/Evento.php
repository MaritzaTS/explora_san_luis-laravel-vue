<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// 🔹 Modelo Evento que representa la tabla 'eventos'
class Evento extends Model
{
    // 🔹 Trait para borrado lógico
    use SoftDeletes;

    // 🔹 Nombre de la tabla
    protected $table = 'eventos';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'lugar_id',
        'nombre',
        'descripcion',
        'url_poster',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    // 🔹 Casts automáticos
    protected $casts = [
        'fecha_inicio' => 'date',   // se convierte a instancia de fecha (Carbon)
        'fecha_fin'    => 'date',
        'estado'       => 'boolean',
    ];

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Un evento pertenece a un lugar
     */
    public function lugar(): BelongsTo
    {
        // foreign key: lugar_id
        return $this->belongsTo(Lugar::class);
    }

    /**
     * 🔹 Un evento tiene múltiples imágenes (galería)
     */
    public function imagenes(): HasMany
    {
        // 🔹 Ordena las imágenes por el campo 'orden'
        return $this->hasMany(ImagenEvento::class)->orderBy('orden');
    }

    // ====================================================
    // 🔹 ACCESSORS
    // ====================================================

    /**
     * 🔹 Genera la URL completa del poster
     * Uso: $evento->url_poster_completa
     */
    public function getUrlPosterCompletaAttribute(): ?string
    {
        return $this->url_poster
            ? asset('storage/' . $this->url_poster)
            : null;
    }

    // ====================================================
    // 🔹 SCOPES
    // ====================================================

    /**
     * 🔹 Filtra solo eventos activos
     * Uso: Evento::activos()->get();
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * 🔹 Eventos vigentes (en curso o futuros)
     * fecha_fin >= hoy
     */
    public function scopeVigentes($query)
    {
        return $query->where('fecha_fin', '>=', now()->toDateString());
    }

    /**
     * 🔹 Eventos pasados (ya finalizaron)
     */
    public function scopePasados($query)
    {
        return $query->where('fecha_fin', '<', now()->toDateString());
    }
}
