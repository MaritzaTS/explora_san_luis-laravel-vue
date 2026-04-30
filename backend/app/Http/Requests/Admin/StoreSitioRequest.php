<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de nuevos Sitios Turísticos.
 *
 * Este Request garantiza que cada sitio turístico nazca con toda la información
 * necesaria, especialmente su galería base de tres imágenes de alta calidad.
 */
class StoreSitioRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene autorización para crear sitios.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Enlace con middleware de permisos si fuera necesario
        return true;
    }

    /**
     * Reglas de validación para la creación de un sitio.
     *
     * Se implementan validaciones estrictas para las imágenes para asegurar
     * la uniformidad visual en el frontend.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // El sitio debe estar anclado a una localidad existente
            'lugar_id'     => ['required', 'integer', 'exists:lugares,id'],

            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['required', 'string'],
            'estado'       => ['nullable', 'boolean'],

            // Reglas de Galería: Tres imágenes obligatorias, formatos modernos y límite de 5MB
            'url_imagen_1' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_imagen_2' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_imagen_3' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Diccionario de mensajes de error personalizados.
     *
     * Proporciona feedback específico al administrador sobre cuál de las tres imágenes
     * está fallando y por qué motivo (tamaño, formato o ausencia).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lugar_id.required'      => 'El lugar es obligatorio.',
            'lugar_id.exists'        => 'El lugar no existe.',
            'nombre.required'        => 'El nombre es obligatorio.',
            'descripcion.required'   => 'La descripción es obligatoria.',

            // Feedback para Imagen 1
            'url_imagen_1.required'  => 'La imagen 1 es obligatoria.',
            'url_imagen_1.image'     => 'La imagen 1 debe ser una imagen válida.',
            'url_imagen_1.mimes'     => 'La imagen 1 debe ser JPG, PNG o WEBP.',
            'url_imagen_1.max'       => 'La imagen 1 no debe superar 5MB.',

            // Feedback para Imagen 2
            'url_imagen_2.required'  => 'La imagen 2 es obligatoria.',
            'url_imagen_2.image'     => 'La imagen 2 debe ser una imagen válida.',
            'url_imagen_2.mimes'     => 'La imagen 2 debe ser JPG, PNG o WEBP.',
            'url_imagen_2.max'       => 'La imagen 2 no debe superar 5MB.',

            // Feedback para Imagen 3
            'url_imagen_3.required'  => 'La imagen 3 es obligatoria.',
            'url_imagen_3.image'     => 'La imagen 3 debe ser una imagen válida.',
            'url_imagen_3.mimes'     => 'La imagen 3 debe ser JPG, PNG o WEBP.',
            'url_imagen_3.max'       => 'La imagen 3 no debe superar 5MB.',
        ];
    }
}
