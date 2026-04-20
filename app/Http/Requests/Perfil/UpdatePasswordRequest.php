<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|min:6|confirmed',
            'password_actual' => [
                'required',
                function($attribute, $value, $fail){
                    if(!Hash::check($value, $this->user()->password)){
                        $fail('La contraseña actual no es correcta.');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'password_actual.required' => 'Las contraseña actual es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ];
    }
}
