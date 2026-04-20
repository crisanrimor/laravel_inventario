<?php

namespace App\Http\Requests\Rol;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('editar-role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->route('role');

        return [
            'name' => 'required|unique:roles,name,'.$role->id,
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
