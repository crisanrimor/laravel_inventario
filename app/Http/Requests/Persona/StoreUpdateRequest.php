<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('crear-persona');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|max:40',
            'email' => 'required|email|max:60',
            'telefono' => 'required|numeric|digits_between:7,20',
            'direccion' => 'required|max:50',
            'tipo_persona' => 'required|in:cliente,proveedor'
        ];
    }
}
