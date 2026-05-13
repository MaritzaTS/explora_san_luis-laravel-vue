<?php

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class StoreComercioPublicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_entidad_id'  => ['required', 'integer', 'exists:tipos_entidad,id'],
            'lugar_id'         => ['required', 'integer', 'exists:lugares,id'],
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'razon_social'     => ['required', 'string', 'max:150'],
            'rut'              => ['required', 'string', 'max:50'],
            'descripcion'      => ['nullable', 'string'],
            'telefono'         => ['required', 'string', 'max:30'],
            'direccion'        => ['required', 'string', 'max:255'],
            'hora_atencion'    => ['required', 'string', 'max:100'],
            'sitio_web'        => ['nullable', 'string', 'url', 'max:255'],
            'subtipos_ids'     => ['nullable', 'array'],
            'subtipos_ids.*'   => ['integer', 'exists:tipos_especificos,id'],
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_entidad_id.required'  => 'El tipo de comercio es obligatorio.',
            'tipo_entidad_id.exists'    => 'El tipo de comercio no existe.',
            'lugar_id.required'         => 'El lugar es obligatorio.',
            'lugar_id.exists'           => 'El lugar no existe.',
            'nombre_comercial.required' => 'El nombre del comercio es obligatorio.',
            'razon_social.required'     => 'La razón social es obligatoria.',
            'rut.required'              => 'El RUT es obligatorio.',
            'telefono.required'         => 'El teléfono es obligatorio.',
            'direccion.required'        => 'La dirección es obligatoria.',
            'hora_atencion.required'    => 'El horario de atención es obligatorio.',
            'sitio_web.url'             => 'El sitio web debe ser una URL válida.',
            'logo.image'               => 'El logo debe ser una imagen.',
            'logo.mimes'               => 'El logo debe ser JPG, PNG o WEBP.',
            'logo.max'                 => 'El logo no debe superar 5MB.',
        ];
    }
}
