<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// 🔹 Modelo Resena que representa la tabla 'resenas'
class Resena extends Model
{
    // 🔹 Trait para borrado lógico
    use SoftDeletes;

    // 🔹 Nombre de la tabla
    protected $table = 'resenas';

    // 🔹 Campos asignables masivamente
    protected $fillable = [
        'usuario_id',
        'comentario',
        'estado',
    ];

    // 🔹 Casts automáticos
    protected $casts = [
        'estado'       => 'boolean', // true / false
    ];

    // ====================================================
    // 🔹 RELACIONES
    // ====================================================

    /**
     * 🔹 Una reseña pertenece a un usuario
     */
    public function usuario(): BelongsTo
    {
        // Laravel asume:
        // - foreign key: usuario_id
        // - owner key: id en usuarios
        return $this->belongsTo(Usuario::class);
    }

    // ====================================================
    // 🔹 SCOPES
    // ====================================================

    /**
     * 🔹 Filtra solo reseñas visibles (activas)
     * Uso: Resena::visibles()->get();
     */
    public function scopeVisibles($query)
    {
        return $query->where('estado', true);
    }
}
