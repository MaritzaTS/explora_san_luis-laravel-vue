<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la actualización de Sitios Turísticos.
 *
 * Permite la modificación de datos descriptivos y geográficos. Las imágenes
 * son opcionales: si no se envían, se conservan las originales en el servidor.
 */
class UpdateSitioRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene permiso para realizar esta acción.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la edición.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // El lugar sigue siendo obligatorio para mantener la integridad relacional
            'lugar_id'     => ['required', 'integer', 'exists:lugares,id'],

            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['required', 'string'],
            'estado'       => ['nullable', 'boolean'],

            /*
             * Imágenes como 'nullable':
             * Esto permite actualizaciones parciales de los metadatos del sitio
             * sin forzar la resubida de archivos binarios.
             */
            'url_imagen_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_imagen_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_imagen_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes de error personalizados para el flujo de actualización.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lugar_id.required'    => 'El lugar es obligatorio.',
            'lugar_id.exists'      => 'El lugar no existe.',
            'nombre.required'      => 'El nombre es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',

            // Mensajes para validación de formato y peso (solo si se intenta subir algo nuevo)
            'url_imagen_1.image'   => 'La imagen 1 debe ser una imagen válida.',
            'url_imagen_2.image'   => 'La imagen 2 debe ser una imagen válida.',
            'url_imagen_3.image'   => 'La imagen 3 debe ser una imagen válida.',
            'url_imagen_1.mimes'   => 'La imagen 1 debe ser JPG, PNG o WEBP.',
            'url_imagen_2.mimes'   => 'La imagen 2 debe ser JPG, PNG o WEBP.',
            'url_imagen_3.mimes'   => 'La imagen 3 debe ser JPG, PNG o WEBP.',
            'url_imagen_1.max'     => 'La imagen 1 no debe superar 5MB.',
            'url_imagen_2.max'     => 'La imagen 2 no debe superar 5MB.',
            'url_imagen_3.max'     => 'La imagen 3 no debe superar 5MB.',
        ];
    }
}
