<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CambiarEstadoRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\UsuarioService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión administrativa de Usuarios.
 *
 * Proporciona endpoints para auditar la lista de usuarios registrados
 * y gestionar sus permisos de acceso mediante el control de estados.
 */
class UsuarioController extends Controller
{
    use ApiResponse;

    /**
     * Inyección del servicio de usuarios.
     */
    public function __construct(
        private readonly UsuarioService $usuarioService,
    ) {}

    /**
     * Obtiene el listado paginado de usuarios del sistema.
     *
     * GET /api/admin/usuarios
     *
     * @return JsonResponse Incluye la colección transformada y metadatos de paginación.
     */
    public function index(): JsonResponse
    {
        $usuarios = $this->usuarioService->listarTodos();

        return $this->success(
            [
                'usuarios'      => UsuarioResource::collection($usuarios),
                'pagina_actual' => $usuarios->currentPage(),
                'total_paginas' => $usuarios->lastPage(),
                'total'         => $usuarios->total(),
            ],
            'Listado de usuarios recuperado exitosamente.'
        );
    }

    /**
     * Activa o desactiva la cuenta de un usuario.
     *
     * PATCH /api/admin/usuarios/{id}/estado
     *
     * Este método delega la seguridad al UsuarioService, el cual impide
     * la desactivación del administrador con ID 1.
     *
     * @param int $id ID del usuario a modificar.
     * @param CambiarEstadoRequest $request Validación del campo booleano 'estado'.
     * @return JsonResponse
     */
    public function cambiarEstado(int $id, CambiarEstadoRequest $request): JsonResponse
    {
        // Obtenemos el valor booleano de forma segura
        $estado = $request->boolean('estado');

        // El servicio lanzará una UsuarioException (403 o 404) si la operación no es permitida
        $usuario = $this->usuarioService->cambiarEstado($id, $estado);

        $mensaje = $estado ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.';

        return $this->success(
            new UsuarioResource($usuario),
            $mensaje
        );
    }
}
