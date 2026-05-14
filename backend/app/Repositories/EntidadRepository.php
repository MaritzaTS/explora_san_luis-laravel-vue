<?php

namespace App\Repositories;

use App\Models\Entidad;
use App\Repositories\Contracts\EntidadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Implementación del repositorio para la entidad Entidad.
 * Utiliza Eloquent para interactuar con la base de datos y aplicar filtros complejos.
 */
class EntidadRepository implements EntidadRepositoryInterface
{
    /**
     * Filtra las entidades basándose en su estado, tipo y subtipos opcionales.
     *
     * @param int $tipoEntidadId ID de la categoría principal.
     * @param array $subtiposIds Lista de IDs de categorías específicas (opcional).
     * @param int $porPagina Número de resultados por página.
     *
     * @return LengthAwarePaginator Objeto de paginación con los datos y metadatos.
     */
    public function filtrarPorTipo(int $tipoEntidadId, array $subtiposIds = [], int $porPagina = 6): LengthAwarePaginator
    {
        // Se encadenan Query Scopes definidos en el modelo Entidad para mejorar la legibilidad.
        return Entidad::activas() // Filtra solo registros con estado activo.
            ->delTipo($tipoEntidadId) // Filtra por la relación de tipo principal.
            ->conSubtipos($subtiposIds) // Filtra por la relación de tipos específicos si se proveen IDs.

            // Eager Loading:
            // Carga las relaciones necesarias para evitar múltiples consultas a la base de datos.
            ->with(['imagenes', 'tiposEspecificos', 'lugar'])

            // Ordena alfabéticamente por el nombre comercial de la entidad.
            ->orderBy('nombre_comercial')

            // Ejecuta la paginación de los resultados.
            ->paginate($porPagina);
    }

    public function listarTodas(int $porPagina = 15, ?int $tipoEntidadId = null): LengthAwarePaginator
    {
        // Construye la consulta cargando relaciones necesarias.
        $query = Entidad::with(['tipoEntidad', 'imagenes', 'tiposEspecificos', 'lugar']);

        // Si se recibe un tipo de entidad, aplica el filtro.
        if ($tipoEntidadId) {
            $query->where('tipo_entidad_id', $tipoEntidadId);
        }

        // Ordena por fecha de creación descendente y pagina resultados.
        return $query->orderBy('created_at', 'desc')->paginate($porPagina);
    }

    public function findById(int $id): ?Entidad
    {
        // Busca una entidad por ID cargando relaciones asociadas.
        return Entidad::with(['tipoEntidad', 'imagenes', 'tiposEspecificos', 'lugar'])->find($id);
    }

    public function create(array $data): Entidad
    {
        // Crea una nueva entidad en la base de datos.
        return Entidad::create($data);
    }

    public function update(Entidad $entidad, array $data): Entidad
    {
        // Actualiza la entidad con los nuevos datos.
        $entidad->update($data);

        // Retorna la entidad actualizada recargando relaciones.
        return $entidad->fresh(['tipoEntidad', 'imagenes', 'tiposEspecificos', 'lugar']);
    }

    public function cambiarEstado(Entidad $entidad, bool $estado): Entidad
    {
        // Actualiza el estado de la entidad.
        $entidad->update(['estado' => $estado]);

        // Retorna la entidad actualizada.
        return $entidad->fresh();
    }

    public function sincronizarSubtipos(Entidad $entidad, array $subtiposIds): void
    {
        // Sincroniza los subtipos en la tabla pivote.
        $entidad->tiposEspecificos()->sync($subtiposIds);
    }
}
