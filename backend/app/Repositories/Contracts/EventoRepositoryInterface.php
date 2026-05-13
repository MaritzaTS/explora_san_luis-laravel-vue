<?php

namespace App\Repositories\Contracts;

use App\Models\Evento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrato para el Repositorio de Eventos.
 *
 * Esta interfaz define los métodos necesarios para la persistencia y consulta
 * de eventos, permitiendo el desacoplamiento entre la lógica de negocio
 * y la capa de acceso a datos.
 */
interface EventoRepositoryInterface
{
    /**
     * Obtiene una colección paginada de todos los eventos.
     *
     * @param int $porPagina Cantidad de registros por cada página.
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator;

    /**
     * Busca un evento específico por su ID único.
     *
     * @param int $id Identificador primario del evento.
     * @return Evento|null El modelo encontrado o null en caso de no existir.
     */
    public function findById(int $id): ?Evento;

    /**
     * Crea un nuevo registro de evento en el sistema de persistencia.
     *
     * @param array $data Atributos del evento (lugar_id, nombre, fechas, etc.).
     * @return Evento Instancia del modelo recién creado.
     */
    public function create(array $data): Evento;

    /**
     * Actualiza un registro de evento existente.
     *
     * @param Evento $evento Instancia del modelo a modificar.
     * @param array $data Nuevos valores para los atributos.
     * @return Evento El modelo con los cambios aplicados y persistidos.
     */
    public function update(Evento $evento, array $data): Evento;

    public function cambiarEstado(Evento $evento, bool $estado): Evento;
}
