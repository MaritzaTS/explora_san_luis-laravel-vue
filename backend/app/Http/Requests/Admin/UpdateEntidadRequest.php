<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la actualización de entidades existentes.
 *
 * Asegura que los datos modificados cumplan con los estándares de integridad
 * antes de ser procesados por el EntidadService.
 */
class UpdateEntidadRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene autorización para editar este recurso.
     *
     * @return bool Retorna true si el usuario posee los permisos administrativos necesarios.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la actualización.
     *
     * Nota: Se mantienen la mayoría de los campos como 'required' para asegurar
     * que el estado final del objeto sea consistente en una petición de tipo PUT.
     *
     * @return array Conjunto de reglas de validación.
     */
    public function rules(): array
    {
        return [
            // Verificación de existencia en tablas maestras
            'tipo_entidad_id'  => ['required', 'integer', 'exists:tipos_entidad,id'],
            'lugar_id'         => ['required', 'integer', 'exists:lugares,id'],

            // Datos de identificación y contacto
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'razon_social'     => ['required', 'string', 'max:150'],
            'rut' => ['nullable', 'string', 'max:50'],
            'descripcion'      => ['nullable', 'string'],
            'telefono'         => ['required', 'string', 'max:30'],
            'direccion'        => ['required', 'string', 'max:255'],
            'hora_atencion'    => ['required', 'string', 'max:100'],

            // Información digital
            'sitio_web'        => ['nullable', 'string', 'url', 'max:255'],

            // Validación de subcategorías (arreglo de IDs)
            'subtipos_ids'     => ['nullable', 'array'],
            'subtipos_ids.*'   => ['integer', 'exists:tipos_especificos,id'],

            // Gestión de archivo: el logo es opcional en la actualización
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes de error personalizados para el usuario del panel administrativo.
     *
     * @return array Lista de mensajes descriptivos.
     */
    public function messages(): array
    {
        return [
            'tipo_entidad_id.required'  => 'El tipo de entidad es obligatorio.',
            'tipo_entidad_id.exists'    => 'El tipo de entidad no existe.',
            'lugar_id.required'         => 'El lugar es obligatorio.',
            'lugar_id.exists'           => 'El lugar no existe.',
            'nombre_comercial.required' => 'El nombre comercial es obligatorio.',
            'razon_social.required'     => 'La razón social es obligatoria.',
            'rut.required'              => 'El RUT es obligatorio.',
            'telefono.required'         => 'El teléfono es obligatorio.',
            'direccion.required'        => 'La dirección es obligatoria.',
            'hora_atencion.required'    => 'El horario de atención es obligatorio.',
            'sitio_web.url'             => 'El sitio web debe ser una URL válida.',
            'logo.image'                => 'El logo debe ser una imagen.',
            'logo.mimes'                => 'El logo debe ser JPG, PNG o WEBP.',
            'logo.max'                  => 'El logo no debe superar 5MB.',
        ];
    }
}
