<?php

namespace App\Services;

use App\DTOs\EntidadDTO;
use App\Exceptions\EntidadException;
use App\Models\Entidad;
use App\Models\ImagenEntidad;
use App\Models\TipoEspecifico;
use App\Repositories\Contracts\EntidadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

/**
 * Servicio de Negocio para la gestión de Entidades.
 *
 * Se encarga de la orquestación entre la persistencia (Repositorios),
 * el almacenamiento de archivos (ImagenService) y las reglas de validación complejas.
 */
class EntidadService
{
    /**
     * Inyección de dependencias para mantener el principio de responsabilidad única.
     */
    public function __construct(
        private readonly EntidadRepositoryInterface $entidadRepository,
        private readonly ImagenService $imagenService,
    ) {}

    /**
     * Obtiene el listado completo de entidades para el panel administrativo.
     * Incluye registros inactivos y utiliza una paginación extendida.
     *
     * @param int $porPagina Cantidad de registros por página (default 15).
     * @return LengthAwarePaginator
     */
    public function listarTodas(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->entidadRepository->listarTodas($porPagina);
    }

    /**
     * Proceso completo de creación de una Entidad.
     *
     * 1. Valida la integridad de las categorías.
     * 2. Persiste la información básica.
     * 3. Gestiona la relación de subtipos.
     * 4. Procesa el logo multimedia si está presente.
     *
     * @param EntidadDTO $dto Datos validados y tipados.
     * @param UploadedFile|null $logo Archivo de imagen opcional.
     * @return Entidad El modelo recién creado con sus relaciones cargadas.
     */
    public function crear(EntidadDTO $dto, ?UploadedFile $logo = null): Entidad
    {
        // Regla de Negocio: No permitir subtipos que no correspondan a la categoría padre.
        $this->validarSubtipos($dto->tipo_entidad_id, $dto->subtipos_ids);

        $entidad = $this->entidadRepository->create([
            'tipo_entidad_id'  => $dto->tipo_entidad_id,
            'lugar_id'         => $dto->lugar_id,
            'nombre_comercial' => $dto->nombre_comercial,
            'razon_social'     => $dto->razon_social,
            'rut'              => $dto->rut,
            'descripcion'      => $dto->descripcion,
            'telefono'         => $dto->telefono,
            'direccion'        => $dto->direccion,
            'hora_atencion'    => $dto->hora_atencion,
            'sitio_web'        => $dto->sitio_web,
        ]);

        // Asociación Many-to-Many de subtipos
        $this->entidadRepository->sincronizarSubtipos($entidad, $dto->subtipos_ids);

        if ($logo) {
            $this->subirLogo($entidad, $logo);
        }

        return $entidad->fresh(['tipoEntidad', 'imagenes', 'tiposEspecificos', 'lugar']);
    }

    /**
     * Actualiza los datos de una entidad existente y gestiona el reemplazo de archivos.
     *
     * @throws EntidadException Si el ID proporcionado no existe.
     */
    public function actualizar(int $id, EntidadDTO $dto, ?UploadedFile $logo = null): Entidad
    {
        $entidad = $this->entidadRepository->findById($id);

        if (!$entidad) {
            throw EntidadException::noEncontrada($id);
        }

        $this->validarSubtipos($dto->tipo_entidad_id, $dto->subtipos_ids);

        $entidad = $this->entidadRepository->update($entidad, [
            'tipo_entidad_id'  => $dto->tipo_entidad_id,
            'lugar_id'         => $dto->lugar_id,
            'nombre_comercial' => $dto->nombre_comercial,
            'razon_social'     => $dto->razon_social,
            'rut'              => $dto->rut,
            'descripcion'      => $dto->descripcion,
            'telefono'         => $dto->telefono,
            'direccion'        => $dto->direccion,
            'hora_atencion'    => $dto->hora_atencion,
            'sitio_web'        => $dto->sitio_web,
        ]);

        $this->entidadRepository->sincronizarSubtipos($entidad, $dto->subtipos_ids);

        if ($logo) {
            $this->subirLogo($entidad, $logo);
        }

        return $entidad->fresh(['tipoEntidad', 'imagenes', 'tiposEspecificos', 'lugar']);
    }

    /**
     * Alterna la visibilidad pública de una entidad.
     */
    public function cambiarEstado(int $id, bool $estado): Entidad
    {
        $entidad = $this->entidadRepository->findById($id);

        if (!$entidad) {
            throw EntidadException::noEncontrada($id);
        }

        return $this->entidadRepository->cambiarEstado($entidad, $estado);
    }

    /**
     * Lógica de reemplazo de Logo: elimina el archivo anterior del disco y BD
     * antes de subir el nuevo para mantener el almacenamiento optimizado.
     */
    public function subirLogo(Entidad $entidad, UploadedFile $archivo): void
    {
        $logoActual = $entidad->imagenes()->first();

        if ($logoActual) {
            $this->imagenService->eliminar($logoActual->url_imagen);
            $logoActual->delete();
        }

        $ruta = $this->imagenService->subir($archivo, 'entidades/logos');

        ImagenEntidad::create([
            'entidad_id'  => $entidad->id,
            'url_imagen'  => $ruta,
            'descripcion' => "Logo de {$entidad->nombre_comercial}",
        ]);
    }

    /**
     * Validación de Integridad Referencial.
     * Verifica que todos los subtipos enviados pertenezcan realmente a la categoría (tipoEntidad) seleccionada.
     *
     * @throws EntidadException Si existe una discrepancia en la jerarquía.
     */
    private function validarSubtipos(int $tipoEntidadId, array $subtiposIds): void
    {
        if (empty($subtiposIds)) {
            return;
        }

        $count = TipoEspecifico::where('tipo_entidad_id', $tipoEntidadId)
            ->whereIn('id', $subtiposIds)
            ->count();

        if ($count !== count($subtiposIds)) {
            throw EntidadException::subtiposInvalidos();
        }
    }
}
