<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso para transformar el modelo Evento en una respuesta JSON.
 * Gestiona la presentación de eventos culturales, festividades o actividades programadas,
 * incluyendo sus fechas, multimedia y ubicación.
 */
class EventoResource extends JsonResource
{
    /**
     * Transforma el recurso en un array para la respuesta HTTP.
     * * @param Request $request El objeto de la petición actual.
     * @return array Estructura de datos formateada para eventos y su cronograma.
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificador único del evento
            'id'           => $this->id,
            // Título o nombre del evento
            'nombre'       => $this->nombre,
            // Información detallada o reseña del evento
            'descripcion'  => $this->descripcion,

            // URL completa del póster o imagen principal.
            // Se asume el uso de un accessor (url_poster_completa) para la ruta absoluta.
            'url_poster'   => $this->url_poster_completa,

            // Formateo de fechas: se presentan en formato legible (Día/Mes/Año)
            'fecha_inicio' => $this->fecha_inicio->format('d/m/Y'),
            'fecha_fin'    => $this->fecha_fin->format('d/m/Y'),

            // Carga Condicional de Lugar: Retorna solo el nombre si la relación está cargada.
            'lugar'        => $this->whenLoaded('lugar', fn() => $this->lugar->nombre),

            // Carga Condicional de Galería: Utiliza un recurso especializado para las imágenes adicionales.
            'imagenes'     => ImagenEventoResource::collection($this->whenLoaded('imagenes')),

            // Estado actual del evento (ej: programado, finalizado, activo)
            'estado'       => $this->estado,

            // Fecha de registro del evento en el sistema
            'created_at'   => $this->created_at->format('d/m/Y'),
        ];
    }
}
