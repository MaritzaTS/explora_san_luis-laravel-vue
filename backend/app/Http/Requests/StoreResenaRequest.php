<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de una reseña desde el sitio público.
 */
class StoreResenaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comentario' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.min'      => 'El comentario debe tener al menos 10 caracteres.',
            'comentario.max'      => 'El comentario no puede superar los 1000 caracteres.',
        ];
    }
}
