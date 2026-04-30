<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Clase encargada de transformar el modelo TipoEspecifico en una estructura JSON.
 * Permite desacoplar la base de datos de la respuesta que recibe el cliente,
 * controlando exactamente qué campos se exponen.
 */
class TipoEspecificoResource extends JsonResource
{
    /**
     * Transforma el recurso en un array para la respuesta HTTP.
     * * @param Request $request La petición entrante.
     * @return array Estructura de datos formateada.
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificador único del tipo específico
            'id'     => $this->id,
            // Nombre descriptivo (ej: "Smartphone", "Tablet")
            'nombre' => $this->nombre,
            // Versión amigable para URL (ej: "smartphone", "tablet")
            'slug'   => $this->slug,
        ];
    }
}
