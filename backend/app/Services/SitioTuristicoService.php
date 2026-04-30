<?php

namespace App\Services;

use App\DTOs\SitioTuristicoDTO;
use App\Exceptions\SitioTuristicoException;
use App\Models\SitioTuristico;
use App\Repositories\Contracts\SitioTuristicoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

/**
 * Servicio encargado de la orquestación de Sitios Turísticos.
 *
 * Gestiona la persistencia de datos, la validación de existencia y el ciclo de vida
 * de las imágenes (carga y reemplazo) asociadas a cada sitio.
 */
class SitioTuristicoService
{
    /**
     * Inyección de dependencias.
     *
     * @param SitioTuristicoRepositoryInterface $sitioRepository Abstracción de la base de datos.
     * @param ImagenService $imagenService Utilidad para manipulación de archivos.
     */
    public function __construct(
        private readonly SitioTuristicoRepositoryInterface $sitioRepository,
        private readonly ImagenService $imagenService,
    ) {}

    /**
     * Obtiene todos los sitios turísticos paginados para administración.
     *
     * @param int $porPagina Cantidad de registros por página.
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->sitioRepository->listarTodos($porPagina);
    }

    /**
     * Crea un nuevo sitio turístico exigiendo tres recursos multimedia.
     *
     * El proceso sube las imágenes en lote y asigna las rutas generadas al modelo.
     *
     * @param SitioTuristicoDTO $dto Datos básicos del sitio.
     * @param UploadedFile $imagen1 Primera imagen obligatoria.
     * @param UploadedFile $imagen2 Segunda imagen obligatoria.
     * @param UploadedFile $imagen3 Tercera imagen obligatoria.
     * @return SitioTuristico
     */
    public function crear(SitioTuristicoDTO $dto, UploadedFile $imagen1, UploadedFile $imagen2, UploadedFile $imagen3): SitioTuristico
    {
        // Subida masiva de imágenes a la carpeta 'sitios'
        $rutas = $this->imagenService->subirMultiples(
            [$imagen1, $imagen2, $imagen3],
            'sitios'
        );

        // Mapeo de datos del DTO y rutas de imágenes al repositorio
        return $this->sitioRepository->create([
            'lugar_id'      => $dto->lugar_id,
            'nombre'        => $dto->nombre,
            'descripcion'   => $dto->descripcion,
            'url_imagen_1'  => $rutas[0],
            'url_imagen_2'  => $rutas[1],
            'url_imagen_3'  => $rutas[2],
            'estado'        => $dto->estado,
        ]);
    }

    /**
     * Actualiza la información de un sitio y reemplaza archivos multimedia si se proveen.
     *
     * Si se adjunta una nueva imagen, el servicio elimina automáticamente la anterior
     * del disco para optimizar el almacenamiento.
     *
     * @param int $id Identificador del sitio.
     * @param SitioTuristicoDTO $dto Datos actualizados.
     * @param UploadedFile|null $imagen1 Nueva imagen 1 (opcional).
     * @param UploadedFile|null $imagen2 Nueva imagen 2 (opcional).
     * @param UploadedFile|null $imagen3 Nueva imagen 3 (opcional).
     * @return SitioTuristico
     * @throws SitioTuristicoException Si el sitio no existe.
     */
    public function actualizar(
        int $id,
        SitioTuristicoDTO $dto,
        ?UploadedFile $imagen1 = null,
        ?UploadedFile $imagen2 = null,
        ?UploadedFile $imagen3 = null
    ): SitioTuristico {
        $sitio = $this->sitioRepository->findById($id);

        if (!$sitio) {
            throw SitioTuristicoException::noEncontrado($id);
        }

        $data = [
            'lugar_id'    => $dto->lugar_id,
            'nombre'      => $dto->nombre,
            'descripcion' => $dto->descripcion,
            'estado'      => $dto->estado,
        ];

        // Lógica de reemplazo selectivo: solo se procesa lo que el usuario envía
        if ($imagen1) {
            $data['url_imagen_1'] = $this->imagenService->reemplazar($imagen1, 'sitios', $sitio->url_imagen_1);
        }
        if ($imagen2) {
            $data['url_imagen_2'] = $this->imagenService->reemplazar($imagen2, 'sitios', $sitio->url_imagen_2);
        }
        if ($imagen3) {
            $data['url_imagen_3'] = $this->imagenService->reemplazar($imagen3, 'sitios', $sitio->url_imagen_3);
        }

        return $this->sitioRepository->update($sitio, $data);
    }

    /**
     * Cambia la visibilidad pública de un sitio turístico.
     *
     * @throws SitioTuristicoException
     */
    public function cambiarEstado(int $id, bool $estado): SitioTuristico
    {
        $sitio = $this->sitioRepository->findById($id);

        if (!$sitio) {
            throw SitioTuristicoException::noEncontrado($id);
        }

        return $this->sitioRepository->cambiarEstado($sitio, $estado);
    }
}
