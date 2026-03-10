<?php

namespace App\Http\Requests;

use App\Enums\TipoPersona;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|max:40',
            'email' => 'required|email|max:60',
            'telefono' => 'required|max:20',
            'direccion' => 'required|max:50',
            'tipo_persona' => ['required', new Enum(TipoPersona::class)],
        ];
    }
}
