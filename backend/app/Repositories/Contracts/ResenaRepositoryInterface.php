<?php

namespace App\Repositories\Contracts;

use App\Models\Resena;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Contrato para el Repositorio de Reseñas.
 *
 * Abstrae el acceso a datos del módulo de reseñas, desacoplando
 * las capas superiores (Service, Controller) de Eloquent.
 */
interface ResenaRepositoryInterface
{
    /**
     * Listado paginado de todas las reseñas (para administración).
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator;

    /**
     * Listado de reseñas visibles (estado = true) para el público.
     */
    public function listarVisibles(): Collection;

    /**
     * Busca una reseña por su identificador primario.
     */
    public function findById(int $id): ?Resena;

    /**
     * Persiste una nueva reseña en la base de datos.
     */
    public function create(array $data): Resena;

    /**
     * Cambia únicamente el estado de visibilidad de una reseña.
     */
    public function cambiarEstado(Resena $resena, bool $estado): Resena;
}
