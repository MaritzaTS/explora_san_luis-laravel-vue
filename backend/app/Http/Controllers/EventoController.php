<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Models\Evento;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión de la agenda de eventos.
 * Se encarga de filtrar y exponer las actividades programadas, festivales y eventos culturales.
 */
class EventoController extends Controller
{
    // Trait para estandarizar las respuestas JSON de la API
    use ApiResponse;

    /**
     * Endpoint: GET /api/eventos
     * Recupera el listado de eventos que están marcados como activos y cuya fecha aún es vigente.
     * * @return JsonResponse Colección de eventos transformada con sus relaciones de lugar y galería.
     */
    public function index(): JsonResponse
    {
        // Se construye la consulta optimizada:
        // 1. Scope 'activos': Filtra eventos habilitados administrativamente.
        // 2. Scope 'vigentes': Filtra eventos cuya fecha de finalización no ha pasado.
        // 3. Eager Loading: Carga 'lugar' e 'imagenes' para evitar consultas adicionales.
        // 4. Ordenamiento: Prioriza los eventos más próximos en el calendario.
        $eventos = Evento::activos()
            ->vigentes()
            ->with(['lugar', 'imagenes'])
            ->orderBy('fecha_inicio')
            ->get();

        // Retorna la respuesta de éxito usando el estándar del proyecto
        return $this->success(
            // Transforma los modelos a través del EventoResource
            EventoResource::collection($eventos),
            'Eventos vigentes.'
        );
    }
}
