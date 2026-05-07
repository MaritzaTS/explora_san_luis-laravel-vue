<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResenaRequest;
use App\Http\Resources\ResenaResource;
use App\Services\ResenaService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador público de Reseñas.
 *
 * Expone únicamente las reseñas aprobadas para el consumo del frontend público.
 */
class ResenaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ResenaService $resenaService,
    ) {}

    /**
     * GET /api/resenas
     *
     * Retorna el listado de reseñas con estado activo (aprobadas por el admin).
     */
    public function index(): JsonResponse
    {
        $resenas = $this->resenaService->listarVisibles();

        return $this->success(
            ResenaResource::collection($resenas),
            'Reseñas.'
        );
    }

    /**
     * POST /api/resenas
     *
     * Crea una reseña pendiente de aprobación. Requiere autenticación.
     */
    public function store(StoreResenaRequest $request): JsonResponse
    {
        $resena = $this->resenaService->crear($request->string('comentario')->trim()->value());

        return $this->created(
            new ResenaResource($resena->load('usuario')),
            'Reseña enviada. Será visible una vez aprobada por el administrador.'
        );
    }
}
