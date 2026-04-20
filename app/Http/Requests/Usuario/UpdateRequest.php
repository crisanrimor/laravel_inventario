<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('editar-usuario');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $usuario = $this->route('usuario');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$usuario->id,
            'rol' => 'required|exists:roles,id'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre'
        ];
    }
}
