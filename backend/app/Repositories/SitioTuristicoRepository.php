<?php

namespace App\Repositories;

use App\Models\SitioTuristico;
use App\Repositories\Contracts\SitioTuristicoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Implementación de Eloquent para el repositorio de Sitios Turísticos.
 *
 * Centraliza las consultas a la base de datos, asegurando que las relaciones
 * necesarias (como 'lugar') se carguen de forma eficiente para evitar el problema de N+1.
 */
class SitioTuristicoRepository implements SitioTuristicoRepositoryInterface
{
    /**
     * Obtiene el listado paginado de sitios turísticos.
     *
     * @param int $porPagina Cantidad de registros por página.
     * @return LengthAwarePaginator Incluye la relación con 'lugar' y ordena por creación.
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return SitioTuristico::with('lugar')
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Busca un sitio por ID incluyendo su ubicación.
     *
     * @param int $id Identificador del sitio.
     * @return SitioTuristico|null
     */
    public function findById(int $id): ?SitioTuristico
    {
        return SitioTuristico::with('lugar')->find($id);
    }

    /**
     * Inserta un nuevo registro en la tabla sitios_turisticos.
     *
     * @param array $data Atributos del modelo (nombre, descripcion, lugar_id, etc).
     * @return SitioTuristico
     */
    public function create(array $data): SitioTuristico
    {
        return SitioTuristico::create($data);
    }

    /**
     * Actualiza un sitio existente y refresca sus relaciones.
     *
     * @param SitioTuristico $sitio Instancia del modelo cargada.
     * @param array $data Datos a modificar.
     * @return SitioTuristico El modelo actualizado con datos frescos de la ubicación.
     */
    public function update(SitioTuristico $sitio, array $data): SitioTuristico
    {
        $sitio->update($data);

        // fresh() asegura que si se cambió el lugar_id, el objeto traiga el modelo 'lugar' correcto
        return $sitio->fresh('lugar');
    }

    /**
     * Actualiza únicamente el booleano de estado.
     *
     * @param SitioTuristico $sitio Instancia del modelo.
     * @param bool $estado Nuevo estado.
     * @return SitioTuristico
     */
    public function cambiarEstado(SitioTuristico $sitio, bool $estado): SitioTuristico
    {
        $sitio->update(['estado' => $estado]);

        return $sitio->fresh();
    }
}
