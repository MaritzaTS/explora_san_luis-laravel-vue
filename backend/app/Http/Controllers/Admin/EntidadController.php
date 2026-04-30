<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\EntidadDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CambiarEstadoRequest;
use App\Http\Requests\Admin\StoreEntidadRequest;
use App\Http\Requests\Admin\UpdateEntidadRequest;
use App\Http\Resources\EntidadResource;
use App\Services\EntidadService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador administrativo para la gestión de Entidades.
 *
 * Proporciona los endpoints necesarios para que los administradores
 * puedan listar, crear, actualizar y moderar el estado de las entidades.
 */
class EntidadController extends Controller
{
    // Trait para respuestas JSON consistentes (success, created, error)
    use ApiResponse;

    /**
     * Inyección del servicio de negocio.
     *
     * @param EntidadService $entidadService Capa de lógica de negocio.
     */
    public function __construct(
        private readonly EntidadService $entidadService,
    ) {}

    /**
     * Endpoint: GET /api/admin/entidades
     * Obtiene el listado paginado de todas las entidades (incluyendo inactivas).
     *
     * @return JsonResponse Metadatos de paginación y colección de entidades.
     */
    public function index(): JsonResponse
    {
        $entidades = $this->entidadService->listarTodas();

        return $this->success(
            [
                'entidades'     => EntidadResource::collection($entidades),
                'pagina_actual' => $entidades->currentPage(),
                'total_paginas' => $entidades->lastPage(),
                'total'         => $entidades->total(),
            ],
            'Listado de entidades.'
        );
    }

    /**
     * Endpoint: POST /api/admin/entidades
     * Procesa la creación de una nueva entidad y su logo.
     *
     * @param StoreEntidadRequest $request Validación de entrada.
     * @return JsonResponse El recurso creado con código 201.
     */
    public function store(StoreEntidadRequest $request): JsonResponse
    {
        // Se transforma el request validado en un objeto de transferencia de datos
        $dto = EntidadDTO::fromRequest($request);
        $logo = $request->file('logo');

        $entidad = $this->entidadService->crear($dto, $logo);

        return $this->created(
            new EntidadResource($entidad),
            'Entidad creada exitosamente.'
        );
    }

    /**
     * Endpoint: PUT /api/admin/entidades/{id}
     * Actualiza los datos de una entidad existente.
     *
     * @param int $id Identificador de la entidad.
     * @param UpdateEntidadRequest $request Validación de campos opcionales/obligatorios.
     * @return JsonResponse Entidad actualizada.
     */
    public function update(int $id, UpdateEntidadRequest $request): JsonResponse
    {
        $dto = EntidadDTO::fromRequest($request);
        $logo = $request->file('logo');

        $entidad = $this->entidadService->actualizar($id, $dto, $logo);

        return $this->success(
            new EntidadResource($entidad),
            'Entidad actualizada exitosamente.'
        );
    }

    /**
     * Endpoint: PATCH /api/admin/entidades/{id}/estado
     * Modifica únicamente la visibilidad (activo/inactivo) de una entidad.
     *
     * @param int $id Identificador de la entidad.
     * @param CambiarEstadoRequest $request Validación del campo booleano.
     * @return JsonResponse
     */
    public function cambiarEstado(int $id, CambiarEstadoRequest $request): JsonResponse
    {
        $estado = $request->boolean('estado');

        $entidad = $this->entidadService->cambiarEstado($id, $estado);

        $mensaje = $estado ? 'Entidad activada.' : 'Entidad desactivada.';

        return $this->success(
            new EntidadResource($entidad),
            $mensaje
        );
    }
}
