<?php

namespace App\Services;

use App\Exceptions\ResenaException;
use App\Models\Resena;
use App\Repositories\Contracts\ResenaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio de Reseñas.
 *
 * Orquesta la lógica de negocio del módulo de reseñas,
 * delegando el acceso a datos al repositorio inyectado.
 */
class ResenaService
{
    public function __construct(
        private readonly ResenaRepositoryInterface $resenaRepository,
    ) {}

    /**
     * Crea una reseña pendiente de aprobación para el usuario autenticado.
     */
    public function crear(string $comentario): Resena
    {
        return $this->resenaRepository->create([
            'usuario_id' => Auth::id(),
            'comentario' => $comentario,
            'estado'     => false,
        ]);
    }

    /**
     * Listado paginado para el panel de administración.
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->resenaRepository->listarTodos($porPagina);
    }

    /**
     * Listado de reseñas aprobadas para el público.
     */
    public function listarVisibles(): Collection
    {
        return $this->resenaRepository->listarVisibles();
    }

    /**
     * Cambia el estado de visibilidad de una reseña.
     *
     * @throws ResenaException Si la reseña no existe.
     */
    public function cambiarEstado(int $id, bool $estado): Resena
    {
        $resena = $this->resenaRepository->findById($id);

        if (!$resena) {
            throw ResenaException::noEncontrada($id);
        }

        return $this->resenaRepository->cambiarEstado($resena, $estado);
    }
}
