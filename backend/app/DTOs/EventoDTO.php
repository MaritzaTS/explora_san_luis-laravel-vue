<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/**
 * Data Transfer Object (DTO) para la gestión de Eventos.
 *
 * Su propósito es transportar los datos de los eventos entre el controlador
 * y el servicio de forma tipada, inmutable y limpia de metadatos de la petición.
 */
class EventoDTO
{
    /**
     * Constructor con propiedades promovidas.
     *
     * Se utiliza 'readonly' para garantizar la integridad de los datos
     * durante todo el proceso de negocio.
     *
     * @param int $lugar_id ID del lugar donde se realizará el evento.
     * @param string $nombre Título o nombre del evento.
     * @param string|null $descripcion Detalle informativo del evento.
     * @param string $fecha_inicio Fecha y hora de comienzo (formato Y-m-d H:i:s).
     * @param string $fecha_fin Fecha y hora de finalización.
     * @param bool $estado Visibilidad del evento (activo/inactivo).
     */
    public function __construct(
        public readonly int $lugar_id,
        public readonly string $nombre,
        public readonly ?string $descripcion,
        public readonly string $fecha_inicio,
        public readonly string $fecha_fin,
        public readonly bool $estado,
    ) {}

    /**
     * Factory Method: Crea una instancia del DTO a partir de una Request de Laravel.
     *
     * Centraliza el casteo de tipos (integer, boolean) y la limpieza de strings.
     *
     * @param Request $request Petición enviada desde el cliente.
     * @return self Instancia lista para ser procesada por el servicio.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // Casteo explícito a entero para evitar errores en base de datos
            lugar_id:     $request->integer('lugar_id'),

            // Limpieza de espacios en blanco en el nombre
            nombre:        $request->string('nombre')->trim()->value(),

            descripcion:  $request->input('descripcion'),

            // Nota: Se asume que las fechas ya vienen validadas por el FormRequest
            fecha_inicio: $request->input('fecha_inicio'),
            fecha_fin:    $request->input('fecha_fin'),

            // Por defecto, un evento se considera activo si no se especifica
            estado:       $request->boolean('estado', true),
        );
    }
}
