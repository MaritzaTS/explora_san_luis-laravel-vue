<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase encargada de validar los datos de entrada para el inicio de sesión.
 * Garantiza que las credenciales lleguen con el formato correcto antes de intentar la autenticación.
 */
class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario tiene permiso para realizar esta solicitud.
     * * @return bool Retorna true para permitir que cualquier visitante acceda al formulario de login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación que deben cumplir los campos de la petición.
     * * @return array Conjunto de reglas para los campos email y password.
     */
    public function rules(): array
    {
        return [
            // El email es obligatorio, debe ser texto y tener un formato de correo electrónico válido
            'email'    => ['required', 'string', 'email'],
            // La contraseña es obligatoria y debe ser una cadena de texto
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Define los mensajes de error personalizados para las fallas de validación.
     * * @return array Mensajes descriptivos en español.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El formato del correo no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
