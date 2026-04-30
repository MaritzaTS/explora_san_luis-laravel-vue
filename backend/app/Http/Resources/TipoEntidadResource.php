<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso para transformar el modelo TipoEntidad en una respuesta JSON.
 * Maneja la lógica de presentación de atributos básicos, rutas de archivos y relaciones.
 */
class TipoEntidadResource extends JsonResource
{
    /**
     * Convierte el recurso en un array para la respuesta HTTP.
     * * @param Request $request El objeto de la petición actual.
     * @return array Estructura de datos con soporte para imágenes y relaciones cargadas.
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificador único de la entidad
            'id'                => $this->id,
            // Nombre de la entidad (ej: "Restaurantes", "Hoteles")
            'nombre'            => $this->nombre,
            // Slug amigable para navegación y SEO
            'slug'              => $this->slug,

            // Genera la URL absoluta de la imagen si existe, de lo contrario devuelve null
            // Se utiliza el helper asset() para apuntar al disco de almacenamiento público
            'url_imagen'        => $this->url_imagen ? asset('storage/' . $this->url_imagen) : null,

            // Carga la colección de tipos específicos solo si la relación ha sido cargada previamente (Eager Loading)
            // Esto evita el problema de consultas N+1 y permite anidar recursos
            'tipos_especificos' => TipoEspecificoResource::collection($this->whenLoaded('tiposEspecificos')),
        ];
    }
}
