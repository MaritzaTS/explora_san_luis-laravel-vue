<?php

namespace App\Repositories\Contracts;

use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

// 🔹 Contrato (interface) para el repositorio de usuarios
// Define qué operaciones se pueden hacer, pero no cómo se implementan
interface UsuarioRepositoryInterface
{
    /**
     * 🔹 Busca un usuario por su email
     * Retorna null si no existe
     */
    public function findByEmail(string $email): ?Usuario;

    /**
     * 🔹 Verifica si ya existe un usuario con ese email
     * Retorna true o false
     */
    public function existsByEmail(string $email): bool;

    /**
     * 🔹 Crea un nuevo usuario en la base de datos
     *
     * @param array<string, mixed> $data
     * Datos necesarios para crear el usuario
     */
    public function create(array $data): Usuario;

    /**
     * 🔹 Actualiza un usuario existente
     *
     * @param array<string, mixed> $data
     * Datos a actualizar
     */
    public function update(Usuario $usuario, array $data): Usuario;

    /**
     * 🔹 Marca un usuario como verificado
     * También limpia el código de verificación asociado
     */
    public function marcarComoVerificado(Usuario $usuario): Usuario;

    public function listarTodos(int $porPagina = 15): LengthAwarePaginator;

    public function cambiarEstado(Usuario $usuario, bool $estado): Usuario;
}
