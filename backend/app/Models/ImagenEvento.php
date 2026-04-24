<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenEvento extends Model
{
    protected $table = 'imagenes_eventos';

    protected $fillable = [
        'evento_id',
        'url_imagen',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    // ====================================================
    // RELACIONES
    // ====================================================

    /**
     * Una imagen pertenece a un evento
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    // ====================================================
    // ACCESSORS
    // ====================================================

    /**
     * URL completa de la imagen
     * Acceder con: $imagen->url_completa
     */
    public function getUrlCompletaAttribute(): string
    {
        return asset('storage/' . $this->url_imagen);
    }
}
