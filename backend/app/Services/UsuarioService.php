<?php

namespace App\Services;

use App\Exceptions\UsuarioException;
use App\Models\Usuario;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Servicio de gestión de usuarios y seguridad de cuentas.
 *
 * Se encarga de la lógica de administración de usuarios, aplicando reglas
 * de protección de cuentas maestras y control de estados de acceso.
 */
class UsuarioService
{
    /**
     * Inyección de dependencias.
     *
     * @param UsuarioRepositoryInterface $usuarioRepository Abstracción de la persistencia de usuarios.
     */
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarioRepository,
    ) {}

    /**
     * Recupera el listado paginado de usuarios del sistema.
     *
     * @param int $porPagina Cantidad de registros por página.
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return $this->usuarioRepository->listarTodos($porPagina);
    }

    /**
     * Modifica el estado de acceso (activo/inactivo) de un usuario.
     *
     * Implementa una validación de seguridad para impedir que el administrador
     * principal sea desactivado, garantizando el acceso perpetuo al panel.
     *
     * @param int $id Identificador del usuario.
     * @param bool $estado Nuevo estado deseado (true = activo, false = inactivo).
     * @return Usuario
     * @throws UsuarioException Si el usuario no existe o si es el admin principal y se intenta desactivar.
     */
    public function cambiarEstado(int $id, bool $estado): Usuario
    {
        // Nota técnica: Se recomienda usar el repositorio para la búsqueda
        // para mantener el desacoplamiento total de Eloquent en esta capa.
        $usuario = Usuario::find($id);

        if (!$usuario) {
            throw UsuarioException::noEncontrado($id);
        }

        /**
         * REGLA DE SEGURIDAD CRÍTICA:
         * Si el usuario es el ID 1 (Administrador Maestro) y se intenta desactivar ($estado = false),
         * se dispara una excepción de prohibición (403 Forbidden).
         */
        if ($usuario->id === 1 && !$estado) {
            throw UsuarioException::noSePuedeDesactivarAdmin();
        }

        return $this->usuarioRepository->cambiarEstado($usuario, $estado);
    }
}
