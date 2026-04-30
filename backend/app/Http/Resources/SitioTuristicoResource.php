<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso para transformar el modelo SitioTuristico en una respuesta JSON.
 * Ideal para mostrar información detallada de puntos de interés turístico,
 * incluyendo su galería multimedia y ubicación.
 */
class SitioTuristicoResource extends JsonResource
{
    /**
     * Transforma el recurso en un array para la respuesta HTTP.
     * * @param Request $request El objeto de la petición actual.
     * @return array Estructura de datos formateada para el consumo del frontend.
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificador único del sitio turístico
            'id'          => $this->id,
            // Nombre del sitio (ej: "Cascada del Amor", "Parque Principal")
            'nombre'      => $this->nombre,
            // Descripción detallada sobre la historia o actividades del lugar
            'descripcion' => $this->descripcion,

            // Galería de imágenes: utiliza un atributo personalizado (accessor)
            // del modelo que debería devolver las URLs ya procesadas.
            'imagenes'    => $this->imagenes_completas,

            // Carga Condicional: Si la relación 'lugar' está cargada, extrae solo el nombre.
            // Esto evita enviar todo el objeto de ubicación si solo se requiere la etiqueta.
            'lugar'       => $this->whenLoaded('lugar', fn() => $this->lugar->nombre),

            // Estado de visibilidad (ej: activo/inactivo)
            'estado'      => $this->estado,

            // Formateo de fecha de registro para visualización administrativa o pública
            'created_at'  => $this->created_at->format('d/m/Y'),
        ];
    }
}
