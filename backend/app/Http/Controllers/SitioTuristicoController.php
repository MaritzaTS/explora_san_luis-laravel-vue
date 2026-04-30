<?php

namespace App\Http\Controllers;

use App\Http\Resources\SitioTuristicoResource;
use App\Models\SitioTuristico;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para gestionar la exposición de sitios turísticos.
 * Proporciona acceso al inventario de puntos de interés y lugares emblemáticos.
 */
class SitioTuristicoController extends Controller
{
    // Trait para estandarizar las respuestas JSON (success, error, etc.)
    use ApiResponse;

    /**
     * Endpoint: GET /api/sitios-turisticos
     * Recupera el listado completo de sitios turísticos que se encuentran en estado activo.
     * * @return JsonResponse Colección de sitios turísticos formateada con su recurso correspondiente.
     */
    public function index(): JsonResponse
    {
        // Se ejecuta la consulta aplicando:
        // 1. Un Query Scope 'activos' para filtrar solo lo que debe ser visible.
        // 2. Eager Loading con 'lugar' para optimizar la obtención de la ubicación.
        // 3. Ordenamiento alfabético por nombre.
        $sitios = SitioTuristico::activos()
            ->with('lugar')
            ->orderBy('nombre')
            ->get();

        // Retorna la respuesta utilizando el formato estandarizado
        return $this->success(
            // Transforma la colección de modelos mediante el Resource específico
            SitioTuristicoResource::collection($sitios),
            'Sitios turísticos.'
        );
    }
}
