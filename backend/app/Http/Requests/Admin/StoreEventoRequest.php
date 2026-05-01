<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de nuevos Eventos.
 *
 * Gestiona la integridad de los datos del evento, con especial énfasis en
 * la coherencia de las fechas y las restricciones de archivos multimedia.
 */
class StoreEventoRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene autorización para crear eventos.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el almacenamiento de un evento.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Relación obligatoria con un lugar existente
            'lugar_id'     => ['required', 'integer', 'exists:lugares,id'],

            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],

            // Validación cronológica: la fecha de fin no puede ser anterior a la de inicio
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after_or_equal:fecha_inicio'],

            'estado'       => ['nullable', 'boolean'],

            // Validación de imagen de promoción (Poster)
            'poster'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes de error personalizados para una mejor experiencia de usuario en el panel.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lugar_id.required'          => 'El lugar es obligatorio.',
            'lugar_id.exists'            => 'El lugar seleccionado no es válido.',
            'nombre.required'            => 'El nombre del evento es obligatorio.',

            // Errores de fechas
            'fecha_inicio.required'      => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date'          => 'La fecha de inicio no tiene un formato válido.',
            'fecha_fin.required'         => 'La fecha de fin es obligatoria.',
            'fecha_fin.date'             => 'La fecha de fin no tiene un formato válido.',
            'fecha_fin.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

            // Errores de archivo
            'poster.image'               => 'El archivo seleccionado debe ser una imagen.',
            'poster.mimes'               => 'El poster debe estar en formato JPG, PNG o WEBP.',
            'poster.max'                 => 'El poster no debe pesar más de 5MB.',
        ];
    }
}
