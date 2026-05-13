<?php

namespace App\Services;

use App\DTOs\EventoDTO;
use App\Exceptions\EventoException;
use App\Models\Evento;
use App\Repositories\Contracts\EventoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

/**
 * Servicio para la gestión de lógica de negocio de Eventos.
 *
 * Se encarga de coordinar el almacenamiento de imágenes (posters),
 * la validación de reglas de negocio y la persistencia mediante repositorios.
 */
class EventoService
{
    /**
     * Inyección de dependencias.
     *
     * @param EventoRepositoryInterface $eventoRepository Acceso a datos de eventos.
     * @param ImagenService $imagenService Utilidad para manipulación de imágenes.
     */
    public function __construct(
        private readonly EventoRepositoryInterface $eventoRepository,
        private readonly ImagenService $imagenService,
    ) {}

    /**
     * Recupera el listado de todos los eventos paginados.
     *
     * @param int $porPagina Cantidad de elementos por página.
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->eventoRepository->listarTodos($porPagina);
    }

    /**
     * Procesa la creación de un nuevo evento.
     *
     * Si se incluye un poster, se sube al almacenamiento y se guarda su ruta.
     *
     * @param EventoDTO $dto Datos validados del evento.
     * @param UploadedFile|null $poster Imagen promocional del evento.
     * @return Evento
     */
    public function crear(EventoDTO $dto, ?UploadedFile $poster = null): Evento
    {
        $data = [
            'lugar_id'     => $dto->lugar_id,
            'nombre'       => $dto->nombre,
            'descripcion'  => $dto->descripcion,
            'fecha_inicio' => $dto->fecha_inicio,
            'fecha_fin'    => $dto->fecha_fin,
            'estado'       => $dto->estado,
        ];

        // Gestión de imagen: se almacena en la carpeta específica de eventos
        if ($poster) {
            $data['url_poster'] = $this->imagenService->subir($poster, 'eventos/posters');
        }

        return $this->eventoRepository->create($data);
    }

    /**
     * Actualiza un evento existente.
     *
     * Incluye lógica de reemplazo de imagen para evitar archivos huérfanos en el disco.
     *
     * @param int $id Identificador del evento.
     * @param EventoDTO $dto Datos actualizados.
     * @param UploadedFile|null $poster Nueva imagen de poster (opcional).
     * @return Evento
     * @throws EventoException Si el evento no existe.
     */
    public function actualizar(int $id, EventoDTO $dto, ?UploadedFile $poster = null): Evento
    {
        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            throw EventoException::noEncontrado($id);
        }

        $data = [
            'lugar_id'     => $dto->lugar_id,
            'nombre'       => $dto->nombre,
            'descripcion'  => $dto->descripcion,
            'fecha_inicio' => $dto->fecha_inicio,
            'fecha_fin'    => $dto->fecha_fin,
            'estado'       => $dto->estado,
        ];

        // Reemplazo de imagen: ImagenService elimina la anterior y sube la nueva
        if ($poster) {
            $data['url_poster'] = $this->imagenService->reemplazar(
                $poster,
                'eventos/posters',
                $evento->url_poster
            );
        }

        return $this->eventoRepository->update($evento, $data);
    }

    public function cambiarEstado(int $id, bool $estado): Evento
    {
        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            throw EventoException::noEncontrado($id);
        }

        return $this->eventoRepository->cambiarEstado($evento, $estado);
    }
}
