<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso para transformar el modelo Entidad en una estructura JSON detallada.
 * Se utiliza principalmente en listados y perfiles detallados de establecimientos o servicios.
 */
class EntidadResource extends JsonResource
{
    /**
     * Transforma el recurso en un array para la respuesta HTTP.
     * * @param Request $request El objeto de la petición actual.
     * @return array Estructura de datos con lógica de imágenes y carga condicional de relaciones.
     */
    public function toArray(Request $request): array
    {
        return [
            // Atributos de identificación y contacto
            'id'                => $this->id,
            'nombre_comercial'  => $this->nombre_comercial,
            'razon_social'      => $this->razon_social,
            'descripcion'       => $this->descripcion,
            'telefono'          => $this->telefono,
            'direccion'         => $this->direccion,
            'hora_atencion'     => $this->hora_atencion,
            'sitio_web'         => $this->sitio_web,
            'estado'            => $this->estado,

            // Lógica de Imagen: Obtiene la primera imagen de la relación si existe y genera su URL absoluta.
            // Si la entidad no tiene imágenes, retorna null.
            'imagen'           => $this->imagenes->first()
                ? asset('storage/' . $this->imagenes->first()->url_imagen)
                : null,

            // Carga Condicional: Solo incluye el recurso TipoEntidad si la relación fue cargada (Eager Loading).
            'tipo_entidad'      => new TipoEntidadResource($this->whenLoaded('tipoEntidad')),

            // Carga Condicional: Transforma la colección de tipos específicos si la relación está disponible.
            'tipos_especificos' => TipoEspecificoResource::collection($this->whenLoaded('tiposEspecificos')),

            // Carga Condicional con Transformación: Retorna solo el nombre del lugar si la relación 'lugar' está cargada.
            'lugar'            => $this->whenLoaded('lugar', fn() => $this->lugar->nombre),

            // Formateo de Fecha: Presenta la fecha de creación en formato legible (Día/Mes/Año).
            'created_at'        => $this->created_at->format('d/m/Y'),
        ];
    }
}
