<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\SitioTuristicoDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CambiarEstadoRequest;
use App\Http\Requests\Admin\StoreSitioRequest;
use App\Http\Requests\Admin\UpdateSitioRequest;
use App\Http\Resources\SitioTuristicoResource;
use App\Services\SitioTuristicoService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador de Administración para Sitios Turísticos.
 *
 * Expone los endpoints para la gestión del catálogo de puntos de interés,
 * integrando validación, transferencia de datos (DTO) y estandarización de respuestas.
 */
class SitioTuristicoController extends Controller
{
    use ApiResponse;

    /**
     * Inyección del servicio de negocio.
     */
    public function __construct(
        private readonly SitioTuristicoService $sitioService,
    ) {}

    /**
     * Lista todos los sitios turísticos con paginación.
     *
     * GET /api/admin/sitios-turisticos
     *
     * @return JsonResponse Incluye la colección de sitios y metadatos de paginación.
     */
    public function index(): JsonResponse
    {
        $sitios = $this->sitioService->listarTodos();

        return $this->success(
            [
                'sitios'        => SitioTuristicoResource::collection($sitios),
                'pagina_actual' => $sitios->currentPage(),
                'total_paginas' => $sitios->lastPage(),
                'total'         => $sitios->total(),
            ],
            'Listado de sitios turísticos.'
        );
    }

    /**
     * Registra un nuevo sitio turístico con sus tres imágenes obligatorias.
     *
     * POST /api/admin/sitios-turisticos
     *
     * @param StoreSitioRequest $request Validación de entrada.
     * @return JsonResponse Código 201 con el recurso creado.
     */
    public function store(StoreSitioRequest $request): JsonResponse
    {
        // Convertimos la petición a un objeto de transferencia inmutable
        $dto = SitioTuristicoDTO::fromRequest($request);

        $sitio = $this->sitioService->crear(
            $dto,
            $request->file('url_imagen_1'),
            $request->file('url_imagen_2'),
            $request->file('url_imagen_3'),
        );

        return $this->created(
            new SitioTuristicoResource($sitio),
            'Sitio turístico creado exitosamente.'
        );
    }

    /**
     * Actualiza la información y/o imágenes de un sitio existente.
     *
     * PUT /api/admin/sitios-turisticos/{id}
     *
     * @param int $id Identificador único del sitio.
     * @param UpdateSitioRequest $request Validación de entrada (imágenes opcionales).
     * @return JsonResponse
     */
    public function update(int $id, UpdateSitioRequest $request): JsonResponse
    {
        $dto = SitioTuristicoDTO::fromRequest($request);

        $sitio = $this->sitioService->actualizar(
            $id,
            $dto,
            $request->file('url_imagen_1'),
            $request->file('url_imagen_2'),
            $request->file('url_imagen_3'),
        );

        return $this->success(
            new SitioTuristicoResource($sitio),
            'Sitio turístico actualizado exitosamente.'
        );
    }

    /**
     * Activa o desactiva la visibilidad de un sitio turístico.
     *
     * PATCH /api/admin/sitios-turisticos/{id}/estado
     *
     * @param int $id ID del sitio.
     * @param CambiarEstadoRequest $request Validación del booleano 'estado'.
     * @return JsonResponse
     */
    public function cambiarEstado(int $id, CambiarEstadoRequest $request): JsonResponse
    {
        $estado = $request->boolean('estado');

        $sitio = $this->sitioService->cambiarEstado($id, $estado);

        $mensaje = $estado ? 'Sitio activado correctamente.' : 'Sitio desactivado correctamente.';

        return $this->success(
            new SitioTuristicoResource($sitio),
            $mensaje
        );
    }
}
