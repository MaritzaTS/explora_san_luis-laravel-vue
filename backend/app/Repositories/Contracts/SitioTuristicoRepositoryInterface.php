<?php

namespace App\Repositories\Contracts;

use App\Models\SitioTuristico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrato para el Repositorio de Sitios Turísticos.
 *
 * Define los métodos necesarios para la persistencia y consulta de puntos
 * de interés, abstrayendo la lógica de Eloquent de las capas superiores.
 */
interface SitioTuristicoRepositoryInterface
{
    /**
     * Recupera un listado paginado de todos los sitios registrados.
     * Útil para la administración global de atractivos turísticos.
     *
     * @param int $porPagina Cantidad de elementos por página.
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator;

    /**
     * Busca un sitio específico por su identificador primario.
     *
     * @param int $id ID del sitio turístico.
     * @return SitioTuristico|null Retorna el modelo o null si no existe.
     */
    public function findById(int $id): ?SitioTuristico;

    /**
     * Persiste un nuevo registro de sitio turístico en la base de datos.
     *
     * @param array $data Datos validados provenientes del DTO.
     * @return SitioTuristico Instancia del modelo creado.
     */
    public function create(array $data): SitioTuristico;

    /**
     * Actualiza la información de un sitio existente.
     *
     * @param SitioTuristico $sitio Instancia del modelo a modificar.
     * @param array $data Datos actualizados.
     * @return SitioTuristico El modelo con los cambios aplicados.
     */
    public function update(SitioTuristico $sitio, array $data): SitioTuristico;

    /**
     * Modifica únicamente la visibilidad pública del sitio.
     *
     * @param SitioTuristico $sitio Instancia del modelo.
     * @param bool $estado Nuevo estado (true para activo, false para inactivo).
     * @return SitioTuristico
     */
    public function cambiarEstado(SitioTuristico $sitio, bool $estado): SitioTuristico;
}
