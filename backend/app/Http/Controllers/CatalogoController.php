<?php

namespace App\Http\Controllers;

use App\Http\Resources\TipoEntidadResource;
use App\Models\TipoEntidad;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\ImagenService;
use App\Http\Requests\Catalogo\ActualizarImagenRequest;


/**
 * Controlador encargado de gestionar las consultas del catálogo público.
 * Proporciona información sobre las categorías y subcategorías disponibles en el sistema.
 */
class CatalogoController extends Controller
{
    // Trait para estandarizar las respuestas JSON de éxito y error
    use ApiResponse;

    public function __construct(
        private readonly ImagenService $imagenService,
    ) {}

    /**
     * Endpoint: GET /api/tipos
     * Obtiene el listado completo de tipos de entidad junto con sus tipos específicos relacionados.
     * * @return JsonResponse Colección de tipos de entidad transformada y mensaje de éxito.
     */
    public function tipos(): JsonResponse
    {
        // Se utiliza Eager Loading (with) para cargar 'tiposEspecificos' en una sola consulta.
        // Esto optimiza el rendimiento evitando el problema de consultas N+1.
        $tipos = TipoEntidad::with('tiposEspecificos')->get();

        // Retorna la respuesta utilizando el formato estandarizado del Trait ApiResponse.
        return $this->success(
            // Transforma la colección de modelos a la estructura definida en el Resource
            TipoEntidadResource::collection($tipos),
            'Catálogo de tipos y subtipos.'
        );
    }

     /**
     * POST /api/admin/tipos/{id}/imagen
     */
    public function actualizarImagen(int $id, ActualizarImagenRequest $request): JsonResponse
    {


        $tipo = TipoEntidad::findOrFail($id);

        $ruta = $this->imagenService->reemplazar(
            $request->file('imagen'),
            'tipos/portadas',
            $tipo->url_imagen
        );

        $tipo->update(['url_imagen' => $ruta]);

        return $this->success(
            new TipoEntidadResource($tipo->load('tiposEspecificos')),
            "Imagen de {$tipo->nombre} actualizada."
        );
    }
}
