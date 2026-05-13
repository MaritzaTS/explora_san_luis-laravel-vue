<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\EventoDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventoRequest;
use App\Http\Requests\Admin\UpdateEventoRequest;
use App\Http\Resources\EventoResource;
use App\Services\EventoService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\CambiarEstadoRequest;

/**
 * Controlador para la gestión administrativa de Eventos.
 *
 * Este controlador orquestra las peticiones HTTP relacionadas con eventos,
 * interactuando con el servicio de negocio y transformando los modelos
 * en respuestas JSON estandarizadas.
 */
class EventoController extends Controller
{
    use ApiResponse;

    /**
     * Inyección del servicio de eventos.
     */
    public function __construct(
        private readonly EventoService $eventoService,
    ) {}

    /**
     * Obtiene el listado de eventos paginado.
     *
     * GET /api/admin/eventos
     *
     * @return JsonResponse Metadatos de paginación y colección de eventos.
     */
    public function index(): JsonResponse
    {
        $eventos = $this->eventoService->listarTodos();

        return $this->success(
            [
                'eventos'        => EventoResource::collection($eventos),
                'pagina_actual' => $eventos->currentPage(),
                'total_paginas' => $eventos->lastPage(),
                'total'         => $eventos->total(),
            ],
            'Listado de eventos.'
        );
    }

    /**
     * Crea un nuevo evento en el sistema.
     *
     * POST /api/admin/eventos
     *
     * @param StoreEventoRequest $request Validación de campos y archivo poster.
     * @return JsonResponse Código 201 con el recurso creado.
     */
    public function store(StoreEventoRequest $request): JsonResponse
    {
        // Transformamos la Request en un DTO inmutable
        $dto = EventoDTO::fromRequest($request);
        $poster = $request->file('poster');

        $evento = $this->eventoService->crear($dto, $poster);

        return $this->created(
            new EventoResource($evento),
            'Evento creado exitosamente.'
        );
    }

    /**
     * Actualiza un evento existente.
     *
     * PUT /api/admin/eventos/{id}
     *
     * @param int $id Identificador del evento.
     * @param UpdateEventoRequest $request Validación de campos y poster opcional.
     * @return JsonResponse
     */
    public function update(int $id, UpdateEventoRequest $request): JsonResponse
    {
        $dto = EventoDTO::fromRequest($request);
        $poster = $request->file('poster');

        $evento = $this->eventoService->actualizar($id, $dto, $poster);

        return $this->success(
            new EventoResource($evento),
            'Evento actualizado exitosamente.'
        );
    }

    /**
     * PATCH /api/admin/eventos/{id}/estado
     */
    public function cambiarEstado(int $id, CambiarEstadoRequest $request): JsonResponse
    {
        $estado = $request->boolean('estado');

        $evento = $this->eventoService->cambiarEstado($id, $estado);

        $mensaje = $estado ? 'Evento activado.' : 'Evento desactivado.';

        return $this->success(
            new EventoResource($evento),
            $mensaje
        );
    }
}
