<?php

namespace App\Http\Requests\Rol;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('crear-role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name',
            'permisos' => 'required|array|min:1',
            'permisos.*' => 'exists:permissions,name'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre'
        ];
    }

    public function messages()
    {
        return [
            'permisos.required' => 'Elige por lo menos 1 permiso.',
            'permisos.*.exists' => 'Uno o más permisos seleccionados son inválidos.'
        ];
    }
}
