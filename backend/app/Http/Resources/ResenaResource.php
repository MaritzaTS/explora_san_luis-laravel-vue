<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso para transformar el modelo Resena en una respuesta JSON estandarizada.
 */
class ResenaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'comentario'  => $this->comentario,
            'estado'      => $this->estado,
            'created_at'  => $this->created_at->format('Y-m-d'),
            'usuario'     => $this->whenLoaded('usuario', fn() => $this->usuario ? [
                'id'     => $this->usuario->id,
                'nombre' => $this->usuario->nombre,
            ] : null),
        ];
    }
}
