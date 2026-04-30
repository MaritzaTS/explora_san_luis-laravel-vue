<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/**
 * Data Transfer Object (DTO) para Sitios Turísticos.
 *
 * Centraliza y tipa los datos necesarios para crear o actualizar un punto
 * de interés, desacoplando la lógica de la petición (Request) del servicio.
 */
class SitioTuristicoDTO
{
    /**
     * Constructor con Propiedades Promovidas.
     *
     * Se utiliza 'readonly' para asegurar que los datos no sean alterados
     * durante el ciclo de vida del proceso de negocio.
     *
     * @param int $lugar_id ID de la zona o localidad (relación con lugares).
     * @param string $nombre Nombre público del sitio turístico.
     * @param string $descripcion Información detallada y atractiva del lugar.
     * @param bool $estado Indica si el sitio es visible públicamente.
     */
    public function __construct(
        public readonly int $lugar_id,
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly bool $estado,
    ) {}

    /**
     * Factory Method: Transforma una Request en una instancia de SitioTuristicoDTO.
     *
     * Realiza el casteo de tipos y la limpieza inicial de cadenas (trim).
     *
     * @param Request $request Petición enviada desde el controlador.
     * @return self Objeto inmutable con los datos del sitio turístico.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            // Asegura que el ID de lugar sea tratado como entero
            lugar_id:    $request->integer('lugar_id'),

            // Limpia espacios accidentales al inicio/final del nombre y descripción
            nombre:      $request->string('nombre')->trim()->value(),
            descripcion: $request->string('descripcion')->trim()->value(),

            // Captura el estado como booleano, por defecto activo (true)
            estado:      $request->boolean('estado', true),
        );
    }
}
