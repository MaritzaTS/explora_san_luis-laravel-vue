<?php

namespace App\Repositories;

use App\Models\Resena;
use App\Repositories\Contracts\ResenaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Implementación Eloquent del repositorio de Reseñas.
 *
 * Centraliza las consultas a la tabla 'resenas', garantizando eager loading
 * de la relación 'usuario' para evitar el problema N+1.
 */
class ResenaRepository implements ResenaRepositoryInterface
{
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return Resena::with('usuario:id,nombre')
            ->orderByDesc('created_at')
            ->paginate($porPagina);
    }

    public function listarVisibles(): Collection
    {
        return Resena::visibles()
            ->with('usuario:id,nombre')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findById(int $id): ?Resena
    {
        return Resena::with('usuario:id,nombre')->find($id);
    }

    public function create(array $data): Resena
    {
        return Resena::create($data);
    }

    public function cambiarEstado(Resena $resena, bool $estado): Resena
    {
        $resena->update(['estado' => $estado]);

        return $resena->fresh('usuario');
    }
}
