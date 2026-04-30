<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/**
 * Data Transfer Object para el manejo de datos de una Entidad.
 *
 * Su propósito es servir como un contenedor de datos inmutable y tipado,
 * evitando el uso de arreglos asociativos genéricos en las capas internas de la aplicación.
 */
class EntidadDTO
{
    /**
     * Constructor con Propiedades Promovidas (PHP 8.2+).
     *
     * Se utiliza 'readonly' para garantizar la inmutabilidad de los datos una vez capturados.
     *
     * @param int $tipo_entidad_id Relación principal con la categoría.
     * @param int $lugar_id Relación con la ubicación geográfica.
     * @param string $nombre_comercial Nombre público del establecimiento.
     * @param string $razon_social Nombre legal de la empresa.
     * @param string $rut Identificación tributaria.
     * @param string|null $descripcion Breve reseña (opcional).
     * @param string $telefono Contacto telefónico.
     * @param string $direccion Ubicación física.
     * @param string $hora_atencion Horarios de servicio.
     * @param string|null $sitio_web Enlace externo opcional.
     * @param array $subtipos_ids Listado de subcategorías asociadas.
     */
    public function __construct(
        public readonly int $tipo_entidad_id,
        public readonly int $lugar_id,
        public readonly string $nombre_comercial,
        public readonly string $razon_social,
        public readonly string $rut,
        public readonly ?string $descripcion,
        public readonly string $telefono,
        public readonly string $direccion,
        public readonly string $hora_atencion,
        public readonly ?string $sitio_web,
        public readonly array $subtipos_ids,
    ) {}

    /**
     * Factory Method: Crea una instancia del DTO a partir de una petición HTTP.
     *
     * Este método centraliza el "limpiado" y casteo de datos de entrada.
     *
     * @param Request $request La petición validada del controlador.
     * @return self Una instancia tipada de los datos de la entidad.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // Casteo explícito a entero para IDs
            tipo_entidad_id:  $request->integer('tipo_entidad_id'),
            lugar_id:         $request->integer('lugar_id'),

            // Limpieza de espacios en blanco en cadenas de texto
            nombre_comercial: $request->string('nombre_comercial')->trim()->value(),
            razon_social:      $request->string('razon_social')->trim()->value(),
            rut:               $request->string('rut')->trim()->value(),

            // Campos que pueden ser nulos
            descripcion:      $request->input('descripcion'),
            telefono:          $request->string('telefono')->trim()->value(),
            direccion:         $request->string('direccion')->trim()->value(),
            hora_atencion:    $request->string('hora_atencion')->trim()->value(),
            sitio_web:        $request->input('sitio_web'),

            // Garantiza que siempre sea un arreglo, incluso si no se envía en el request
            subtipos_ids:      $request->input('subtipos_ids', []),
        );
    }
}
