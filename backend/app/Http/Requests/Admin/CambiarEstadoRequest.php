<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación simplificada para el cambio de estado de un recurso.
 *
 * Se utiliza en endpoints de tipo PATCH o PUT destinados únicamente a
 * habilitar o deshabilitar entidades, sitios o eventos.
 */
class CambiarEstadoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para ejecutar esta acción.
     *
     * @return bool Generalmente restringido a roles administrativos en el panel.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la actualización del estado.
     *
     * @return array Regla para asegurar que el valor sea un booleano puro.
     */
    public function rules(): array
    {
        return [
            // El campo es obligatorio y debe ser interpretable como booleano (true, false, 1, 0, "1", "0")
            'estado' => ['required', 'boolean'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean'  => 'El estado debe ser verdadero o falso.',
        ];
    }
}
