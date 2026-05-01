<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entidad;
use App\Models\Evento;
use App\Models\SitioTuristico;
use App\Models\TipoEntidad;
use App\Models\Usuario;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para el panel de control (Dashboard).
 *
 * Centraliza la recopilación de métricas y estadísticas globales para
 * ofrecer un resumen ejecutivo de la plataforma al administrador.
 */
class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Recupera las estadísticas generales del sistema.
     *
     * GET /api/admin/dashboard/stats
     *
     * Realiza un conteo filtrado de los recursos activos para alimentar
     * los gráficos y contadores del panel principal.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        /**
         * Cálculo de entidades activas segmentadas por tipo.
         * Se utiliza withCount con un filtro de estado para optimizar la consulta SQL.
         */
        $entidadesPorTipo = TipoEntidad::withCount(['entidades' => function ($query) {
            $query->where('estado', true);
        }])->get()->map(fn($tipo) => [
            'tipo'  => $tipo->nombre,
            'slug'  => $tipo->slug,
            'total' => $tipo->entidades_count,
        ]);

        /**
         * Resumen global de métricas.
         * Nota: Solo se cuentan los registros con 'estado = true' para reflejar
         * lo que el usuario final ve actualmente en la plataforma.
         */
        $stats = [
            'entidades_por_tipo'   => $entidadesPorTipo,
            'total_entidades'      => Entidad::where('estado', true)->count(),
            'total_sitios'         => SitioTuristico::where('estado', true)->count(),
            'total_eventos'        => Evento::where('estado', true)->count(),
            'total_usuarios'       => Usuario::count(),
            'usuarios_verificados' => Usuario::where('verificado', true)->count(),
        ];

        return $this->success($stats, 'Estadísticas del dashboard recuperadas.');
    }
}
