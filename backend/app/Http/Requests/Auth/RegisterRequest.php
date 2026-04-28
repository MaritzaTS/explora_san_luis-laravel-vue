<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

// 🔹 Request para validar el registro de usuarios
class RegisterRequest extends FormRequest
{
    /**
     * 🔹 Define si la petición está autorizada
     * true → cualquier usuario (incluso no autenticado) puede registrarse
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 🔹 Reglas de validación
     */
    public function rules(): array
    {
        return [
            // 🔹 Nombre obligatorio, tipo string, entre 3 y 150 caracteres
            'nombre' => ['required', 'string', 'min:3', 'max:150'],

            // 🔹 Email obligatorio, formato válido, máximo 150 caracteres
            'email' => ['required', 'string', 'email', 'max:150'],

            // 🔹 Contraseña obligatoria, entre 8 y 60 caracteres
            'password' => ['required', 'string', 'min:8', 'max:60'],
        ];
    }

    /**
     * 🔹 Mensajes personalizados en español
     */
    public function messages(): array
    {
        return [
            // 🔹 Nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max'      => 'El nombre no puede superar los 150 caracteres.',

            // 🔹 Email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'El formato del correo no es válido.',
            'email.max'      => 'El correo no puede superar los 150 caracteres.',

            // 🔹 Password
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'      => 'La contraseña no puede superar los 60 caracteres.',
        ];
    }
}
