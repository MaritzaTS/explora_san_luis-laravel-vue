<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de nuevas entidades desde el panel administrativo.
 *
 * Este Request centraliza las reglas de validación de campos obligatorios,
 * tipos de datos, existencia de relaciones y restricciones de archivos multimedia.
 */
class StoreEntidadRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene permiso para realizar esta acción.
     *
     * @return bool Retorna true si el usuario está autenticado y autorizado (ej. mediante Roles/Permissions).
     */
    public function authorize(): bool
    {
        // En una implementación real, aquí se verificaría el permiso de 'crear-entidades'
        return true;
    }

    /**
     * Define las reglas de validación que se aplicarán a la petición.
     *
     * @return array Conjunto de reglas para campos de texto, IDs y archivos.
     */
    public function rules(): array
    {
        return [
            // Integridad Referencial: El tipo y lugar deben existir en sus tablas respectivas
            'tipo_entidad_id'  => ['required', 'integer', 'exists:tipos_entidad,id'],
            'lugar_id'         => ['required', 'integer', 'exists:lugares,id'],

            // Datos básicos con límites de caracteres para optimizar la BD
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'razon_social'     => ['required', 'string', 'max:150'],
            'rut'              => ['required', 'string', 'max:50'],
            'descripcion'      => ['nullable', 'string'],
            'telefono'         => ['required', 'string', 'max:30'],
            'direccion'        => ['required', 'string', 'max:255'],
            'hora_atencion'    => ['required', 'string', 'max:100'],

            // Validación de formato URL para el sitio web
            'sitio_web'        => ['nullable', 'string', 'url', 'max:255'],

            // Validación de arreglo para relaciones Many-to-Many
            'subtipos_ids'     => ['nullable', 'array'],
            'subtipos_ids.*'   => ['integer', 'exists:tipos_especificos,id'],

            // Reglas para el logo: tipo de archivo, extensiones permitidas y peso (5MB)
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Personaliza los mensajes de error para una mejor experiencia del administrador.
     *
     * @return array Mensajes en español para las reglas más críticas.
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
