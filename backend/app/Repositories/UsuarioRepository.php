<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Repositories\Contracts\UsuarioRepositoryInterface;

// 🔹 Implementación del repositorio de usuarios
// Aquí se define la lógica concreta de acceso a datos
class UsuarioRepository implements UsuarioRepositoryInterface
{
    /**
     * 🔹 Busca un usuario por su email
     * Retorna null si no existe
     */
    public function findByEmail(string $email): ?Usuario
    {
        return Usuario::where('email', $email)->first();
    }

    /**
     * 🔹 Verifica si existe un usuario con ese email
     */
    public function existsByEmail(string $email): bool
    {
        return Usuario::where('email', $email)->exists();
    }

    /**
     * 🔹 Crea un nuevo usuario
     */
    public function create(array $data): Usuario
    {
        // 🔹 Usa asignación masiva (fillable en el modelo)
        return Usuario::create($data);
    }

    /**
     * 🔹 Actualiza un usuario existente
     */
    public function update(Usuario $usuario, array $data): Usuario
    {
        // 🔹 Actualiza los datos
        $usuario->update($data);

        // 🔹 fresh() recarga el modelo desde la BD (datos actualizados)
        return $usuario->fresh();
    }

    /**
     * 🔹 Marca el usuario como verificado
     * Limpia datos relacionados con verificación
     */
    public function marcarComoVerificado(Usuario $usuario): Usuario
    {
        $usuario->update([
            'verificado'          => true,   // usuario ya verificado
            'codigo_verificacion' => null,   // elimina código
            'codigo_expira_en'    => null,   // elimina expiración
            'intentos_codigo'     => 0,      // reinicia intentos
        ]);

        // 🔹 Retorna el modelo actualizado desde la BD
        return $usuario->fresh();
    }
}
