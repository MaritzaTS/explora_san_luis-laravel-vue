<?php

namespace App\Http\Middleware;

use App\Enums\RolEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autorización para verificar privilegios de administrador.
 * Actúa como una capa de seguridad que intercepta la petición antes de llegar al controlador.
 */
class IsAdmin
{
    /**
     * Maneja la petición entrante y verifica el rol del usuario.
     * * @param Request $request La petición HTTP actual.
     * @param Closure $next El siguiente paso en la tubería (middleware o controlador).
     * @return Response Respuesta JSON de error 403 o continuación del flujo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recupera la instancia del usuario autenticado desde el objeto Request
        $user = $request->user();

        // Evalúa si no hay sesión activa o si el ID del rol no coincide con el valor 'ADMIN' del Enum
        if (!$user || $user->rol_id !== RolEnum::ADMIN->value) {
            // Si la validación falla, se bloquea el acceso con una respuesta estandarizada
            return response()->json([
                'status'  => 'error',
                'message' => 'No tienes permisos de administrador.',
                'error'   => 'FORBIDDEN',
            ], 403);
        }

        // Si el usuario es administrador, la petición sigue su curso normal
        return $next($request);
    }
}
