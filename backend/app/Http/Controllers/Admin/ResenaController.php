<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CambiarEstadoRequest;
use App\Http\Resources\ResenaResource;
use App\Services\ResenaService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador administrativo de Reseñas.
 *
 * Gestiona la moderación de comentarios: listado paginado y aprobación/rechazo.
 */
class ResenaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ResenaService $resenaService,
    ) {}

    /**
     * GET /api/admin/resenas
     *
     * Listado paginado de todas las reseñas para el panel de administración.
     */
    public function index(): JsonResponse
    {
        $resenas = $this->resenaService->listarTodos();

        return $this->success(
            [
                'resenas'       => ResenaResource::collection($resenas),
                'pagina_actual' => $resenas->currentPage(),
                'total_paginas' => $resenas->lastPage(),
                'total'         => $resenas->total(),
            ],
            'Listado de reseñas.'
        );
    }

    /**
     * PATCH /api/admin/resenas/{id}/estado
     *
     * Aprueba o rechaza una reseña cambiando su visibilidad pública.
     */
    public function cambiarEstado(int $id, CambiarEstadoRequest $request): JsonResponse
    {
        $resena = $this->resenaService->cambiarEstado($id, $request->boolean('estado'));

        return $this->success(
            new ResenaResource($resena),
            'Estado de la reseña actualizado.'
        );
    }
}
