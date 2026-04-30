<?php

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Clase de validación para el filtrado de entidades.
 * Se encarga de validar los parámetros de búsqueda y paginación enviados por el cliente.
 */
class FiltrarEntidadesRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     * * @return bool Retorna true para permitir el acceso público a los filtros.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para los parámetros de filtro.
     * * @return array Reglas para el manejo de arreglos de subtipos y paginación.
     */
    public function rules(): array
    {
        return [
            // El campo 'subtipos' es opcional, pero si se envía, debe ser un arreglo
            'subtipos'   => ['nullable', 'array'],
            // Validación profunda del arreglo: cada elemento debe ser un entero positivo
            'subtipos.*' => ['integer', 'min:1'],
            // El parámetro 'page' es opcional y debe ser un número entero para la paginación
            'page'       => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Personaliza los mensajes de error en español.
     * * @return array Mensajes descriptivos para el usuario o desarrollador frontend.
     */
    public function messages(): array
    {
        return [
            'subtipos.array'      => 'Los subtipos deben ser un listado.',
            'subtipos.*.integer' => 'Cada subtipo debe ser un número válido.',
        ];
    }
}
