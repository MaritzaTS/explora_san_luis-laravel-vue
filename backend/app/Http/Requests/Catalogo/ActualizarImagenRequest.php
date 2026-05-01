<?php

namespace App\Http\Requests\Catalogo;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarImagenRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Cámbialo a true si el acceso lo controla el middleware de la ruta
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'imagen' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes personalizados (Opcional pero recomendado)
     */
    public function messages(): array
    {
        return [
            'imagen.required' => 'Debes subir una imagen.',
            'imagen.max' => 'La imagen no debe pesar más de 5MB.',
        ];
    }
}
