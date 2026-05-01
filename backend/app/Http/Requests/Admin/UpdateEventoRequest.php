<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la actualización de Eventos existentes.
 *
 * Asegura que los cambios mantengan la integridad cronológica y relacional,
 * permitiendo actualizaciones parciales de metadatos o el reemplazo opcional del poster.
 */
class UpdateEventoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para editar eventos.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la actualización.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Se mantiene la obligatoriedad del lugar para evitar registros huérfanos
            'lugar_id'     => ['required', 'integer', 'exists:lugares,id'],

            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],

            // Validación de fechas para evitar solapamientos lógicos inversos
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after_or_equal:fecha_inicio'],

            'estado'       => ['nullable', 'boolean'],

            // El poster es opcional: si se envía, debe cumplir los estándares de calidad
            'poster'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lugar_id.required'          => 'El lugar es obligatorio.',
            'lugar_id.exists'            => 'El lugar seleccionado no existe.',
            'nombre.required'            => 'El nombre es obligatorio.',

            'fecha_inicio.required'      => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date'          => 'La fecha de inicio no es válida.',
            'fecha_fin.required'         => 'La fecha de fin es obligatoria.',
            'fecha_fin.date'             => 'La fecha de fin no es válida.',
            'fecha_fin.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

            'poster.image'               => 'El poster debe ser un archivo de imagen.',
            'poster.mimes'               => 'El poster debe ser un formato válido (JPG, PNG, WEBP).',
            'poster.max'                 => 'El poster no debe superar los 5MB.',
        ];
    }
}
