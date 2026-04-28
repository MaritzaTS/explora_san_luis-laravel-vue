<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase de validación para la petición de verificación de código.
 * Se encarga de centralizar las reglas de seguridad y los mensajes de error
 * antes de que los datos lleguen al controlador o servicio.
 */
class VerificarCodigoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     * * @return bool Devuelve true para permitir que cualquier usuario intente verificar el código.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación que se aplicarán a los datos recibidos.
     * * @return array Conjunto de reglas para los campos email y código.
     */
    public function rules(): array
    {
        return [
            // El email debe estar presente, ser una cadena de texto y tener formato de correo válido
            'email'  => ['required', 'string', 'email'],
            // El código debe ser obligatorio y tener una longitud exacta de 6 caracteres
            'codigo' => ['required', 'string', 'size:6'],
        ];
    }

    /**
     * Personaliza los mensajes de error que se devuelven cuando falla una validación.
     * * @return array Listado de mensajes en español para mejorar la experiencia del usuario.
     */
    public function messages(): array
    {
        return [
            'email.required'  => 'El correo electrónico es obligatorio.',
            'email.email'     => 'El formato del correo no es válido.',
            'codigo.required' => 'El código de verificación es obligatorio.',
            'codigo.size'     => 'El código debe tener exactamente 6 caracteres.',
        ];
    }
}
