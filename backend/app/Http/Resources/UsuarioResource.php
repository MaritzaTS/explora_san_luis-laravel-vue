<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// 🔹 Resource para transformar el modelo Usuario a JSON
class UsuarioResource extends JsonResource
{
    /**
     * 🔹 Convierte el modelo en un array listo para API
     * Solo expone los datos necesarios al frontend
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 🔹 Identificador único
            'id' => $this->id,

            // 🔹 Datos básicos del usuario
            'nombre' => $this->nombre,
            'email'  => $this->email,

            // 🔹 Estado del usuario
            'verificado' => $this->verificado,
            'estado'     => $this->estado,

            // 🔹 Información del rol
            'rol' => [
                'id' => $this->rol_id,

                // 🔹 Solo incluye el nombre si la relación 'rol' está cargada (eager loading)
                'nombre' => $this->whenLoaded('rol', fn() => $this->rol->nombre),
            ],

            // 🔹 Fecha de creación formateada para el frontend
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
