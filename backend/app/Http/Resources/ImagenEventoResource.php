<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso encargado de transformar las imágenes individuales de la galería de un evento.
 * Permite estandarizar la entrega de archivos multimedia adicionales asociados a un registro.
 */
class ImagenEventoResource extends JsonResource
{
    /**
     * Transforma el recurso de imagen en un array para la respuesta HTTP.
     * * @param Request $request El objeto de la petición actual.
     * @return array Estructura de datos que incluye la URL procesada y metadatos de la imagen.
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificador único del registro de la imagen
            'id'          => $this->id,

            // URL completa y absoluta para acceder al archivo.
            // Se utiliza el atributo calculado 'url_completa' definido en el modelo.
            'url_imagen'  => $this->url_completa,

            // Texto alternativo o descripción breve de la fotografía
            'descripcion' => $this->descripcion,

            // Valor numérico que determina la posición de la imagen dentro de la galería.
            // Útil para permitir que el administrador organice visualmente el contenido.
            'orden'       => $this->orden,
        ];
    }
}
