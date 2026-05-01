<?php

namespace App\Repositories;

use App\Models\Evento;
use App\Repositories\Contracts\EventoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Repositorio para la gestión de Eventos con Eloquent.
 *
 * Implementa la interfaz de contrato para asegurar que el acceso a datos
 * sea consistente y permita la carga optimizada de relaciones.
 */
class EventoRepository implements EventoRepositoryInterface
{
    /**
     * Recupera todos los eventos paginados, cargando su ubicación.
     *
     * @param int $porPagina Cantidad de registros por página.
     * @return LengthAwarePaginator Ordenados por fecha de inicio más reciente.
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        // Se utiliza with('lugar') para evitar el problema de consultas N+1
        return Evento::with('lugar')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Busca un evento por su ID junto con la información de su lugar.
     *
     * @param int $id ID del evento.
     * @return Evento|null
     */
    public function findById(int $id): ?Evento
    {
        return Evento::with('lugar')->find($id);
    }

    /**
     * Persiste un nuevo evento en la base de datos.
     *
     * @param array $data Atributos validados del evento.
     * @return Evento
     */
    public function create(array $data): Evento
    {
        return Evento::create($data);
    }

    /**
     * Actualiza un evento existente y recarga sus relaciones.
     *
     * @param Evento $evento Instancia del modelo cargado.
     * @param array $data Nuevos valores.
     * @return Evento El modelo actualizado con la relación 'lugar' fresca.
     */
    public function update(Evento $evento, array $data): Evento
    {
        $evento->update($data);

        // fresh('lugar') es crucial si se actualizó el lugar_id,
        // para que el objeto devuelto refleje la nueva relación.
        return $evento->fresh('lugar');
    }
}
