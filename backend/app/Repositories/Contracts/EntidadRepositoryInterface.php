<?php

namespace App\Repositories\Contracts;

use App\Models\Entidad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interfaz EntidadRepositoryInterface
 * * Define el contrato para las operaciones de persistencia y consulta de Entidades.
 * El uso de interfaces permite el desacoplamiento y facilita las pruebas unitarias (Mocking).
 */
interface EntidadRepositoryInterface
{
    /**
     * Filtra entidades que se encuentren en estado activo basándose en su tipo y subtipos.
     * * El resultado se entrega paginado para optimizar el consumo de recursos y la carga en el frontend.
     *
     * @param int $tipoEntidadId Identificador único del tipo de entidad principal.
     * @param array<int> $subtiposIds Lista de IDs de tipos específicos para filtrar (opcional).
     * @param int $porPagina Cantidad de registros a mostrar por cada página (por defecto 6).
     * @return LengthAwarePaginator Objeto de paginación compatible con Laravel que contiene los resultados.
     */
    public function filtrarPorTipo(int $tipoEntidadId, array $subtiposIds = [], int $porPagina = 6): LengthAwarePaginator;

    public function listarTodas(int $porPagina = 15): LengthAwarePaginator;

    public function findById(int $id): ?Entidad;

    public function create(array $data): Entidad;

    public function update(Entidad $entidad, array $data): Entidad;

    public function cambiarEstado(Entidad $entidad, bool $estado): Entidad;

    public function sincronizarSubtipos(Entidad $entidad, array $subtiposIds): void;
}
