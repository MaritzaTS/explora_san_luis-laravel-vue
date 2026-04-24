<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// 🔹 Modelo Rol que representa la tabla 'roles'
class Rol extends Model
{
    // 🔹 Nombre de la tabla en la base de datos
    protected $table = 'roles';

    // 🔹 Campos que se pueden asignar masivamente (mass assignment)
    protected $fillable = ['nombre'];

    /**
     * 🔹 Relación: Un rol tiene muchos usuarios
     */
    public function usuarios(): HasMany
    {
        // 🔹 Relación hasMany por convención:
        // Laravel asume:
        // - foreign key: rol_id en la tabla usuarios
        // - local key: id en la tabla roles
        return $this->hasMany(Usuario::class);
    }
}
