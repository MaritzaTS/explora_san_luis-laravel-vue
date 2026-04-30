<?php

namespace App\Services;

use App\Exceptions\ImagenException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Servicio encargado de la gestión de archivos de imagen en el sistema.
 * Abstrae la lógica de almacenamiento para asegurar nombres únicos y limpieza de disco.
 */
class ImagenService
{
    private array $formatosPermitidos = ['jpg', 'jpeg', 'png', 'webp'];
    private int $tamanoMaximoMb = 5;
    /**
     * Sube una imagen al disco público y retorna la ruta relativa.
     *
     * Usa UUIDs para evitar colisiones de nombres de archivos y organiza por carpetas.
     *
     * @param UploadedFile $archivo El archivo recibido desde el request.
     * @param string $carpeta Carpeta de destino (ej: 'entidades/logos', 'eventos/posters').
     * @return string Ruta relativa generada (ej: 'carpeta/uuid.png') para persistir en BD.
     */
    public function subir(UploadedFile $archivo, string $carpeta): string
    {
        $this->validar($archivo);
        // Genera un nombre único universal para evitar sobrescribir archivos existentes
        $nombre = Str::uuid() . '.' . $archivo->getClientOriginalExtension();

        // Almacena el archivo en el disco 'public' (configurado en filesystems.php)
        $ruta = $archivo->storeAs($carpeta, $nombre, 'public');

        if (!$ruta) {
            throw ImagenException::falloAlSubir();
        }


        return $ruta;
    }

    /**
     * Reemplaza una imagen existente por una nueva.
     *
     * Es ideal para actualizaciones de perfiles o edición de eventos,
     * optimizando el espacio en disco al eliminar el recurso obsoleto.
     *
     * @param UploadedFile $archivo Nuevo archivo a subir.
     * @param string $carpeta Carpeta de destino.
     * @param string|null $rutaAnterior Ruta de la imagen vieja que debe ser borrada.
     * @return string Nueva ruta relativa generada.
     */
    public function reemplazar(UploadedFile $archivo, string $carpeta, ?string $rutaAnterior = null): string
    {
        // Si se proporciona una ruta previa, se procede a su eliminación física
        if ($rutaAnterior) {
            $this->eliminar($rutaAnterior);
        }

        return $this->subir($archivo, $carpeta);
    }

    /**
     * Elimina físicamente una imagen del almacenamiento.
     *
     * @param string $ruta Ruta relativa almacenada en la base de datos.
     * @return void
     */
    public function eliminar(string $ruta): void
    {
        // Verifica la existencia del archivo antes de intentar borrarlo para evitar errores
        if (Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }
    }

    /**
     * Procesa la subida de un conjunto de imágenes (galerías).
     *
     * @param UploadedFile[] $archivos Array de archivos de imagen.
     * @param string $carpeta Carpeta de destino común para el lote.
     * @return string[] Listado de rutas relativas generadas.
     */
    public function subirMultiples(array $archivos, string $carpeta): array
    {
        $rutas = [];

        foreach ($archivos as $archivo) {
            // Reutiliza la lógica de subida unitaria para cada elemento
            $rutas[] = $this->subir($archivo, $carpeta);
        }

        return $rutas;
    }

    /**
     * Valida formato y tamaño del archivo.
     */
    private function validar(UploadedFile $archivo): void
    {
        $extension = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($extension, $this->formatosPermitidos)) {
            throw ImagenException::formatoNoPermitido();
        }

        $tamanoMb = $archivo->getSize() / 1024 / 1024;

        if ($tamanoMb > $this->tamanoMaximoMb) {
            throw ImagenException::tamanoExcedido();
        }
    }
}
