<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shared\FiltrarEntidadesRequest;
use App\Http\Resources\EntidadResource;
use App\Models\TipoEntidad;
use App\Repositories\Contracts\EntidadRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la exposición pública de entidades.
 * Maneja la navegación y el filtrado por categorías desde la perspectiva del usuario final.
 */
class EntidadPublicaController extends Controller
{
    // Trait para estandarizar las respuestas JSON de la API
    use ApiResponse;

    /**
     * Inyección del repositorio mediante contrato.
     * * @param EntidadRepositoryInterface $entidadRepository Implementación del repositorio de entidades.
     */
    public function __construct(
        private readonly EntidadRepositoryInterface $entidadRepository,
    ) {}

    /**
     * Endpoint: GET /api/entidades/{tipoEntidad:slug}
     * Lista y filtra entidades basadas en un tipo (slug) y subtipos opcionales.
     * * Ejemplo de uso: /api/entidades/gastronomia?subtipos[]=1&subtipos[]=3&page=2
     *
     * @param TipoEntidad $tipoEntidad Modelo recuperado automáticamente mediante el slug en la URL.
     * @param FiltrarEntidadesRequest $request Objeto de petición validado con los filtros.
     * @return JsonResponse Respuesta estructurada con datos paginados.
     */
    public function index(TipoEntidad $tipoEntidad, FiltrarEntidadesRequest $request): JsonResponse
    {
        // Recupera los IDs de los subtipos desde la query string, por defecto un array vacío
        $subtipos = $request->input('subtipos', []);

        // Delega la lógica de consulta al repositorio para mantener el controlador limpio
        $entidades = $this->entidadRepository->filtrarPorTipo(
            tipoEntidadId: $tipoEntidad->id,
            subtiposIds: $subtipos,
        );

        // Retorna una respuesta de éxito con la colección de datos y metadatos de paginación
        return $this->success(
            [
                // Transforma los modelos Entidad a través del recurso definido
                'entidades'     => EntidadResource::collection($entidades),
                // Información de control para la navegación en el frontend
                'pagina_actual' => $entidades->currentPage(),
                'total_paginas' => $entidades->lastPage(),
                'total'         => $entidades->total(),
                'por_pagina'    => $entidades->perPage(),
            ],
            "Entidades de {$tipoEntidad->nombre}."
        );
    }
}
