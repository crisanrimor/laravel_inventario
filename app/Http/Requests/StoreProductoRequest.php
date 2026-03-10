<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'nombre' => 'required|max:20',
            'descripcion' => 'nullable|max:50',
            'sku' => 'required|max:10|unique:productos,sku',
            'precio' => 'required',
            'costo' => 'required',
            'stock_minimo' => 'nullable|integer',
            'stock_actual' => 'nullable|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'img' => 'file|mimes:jpg,png,webp|max:500'
        ];
    }

    //Sirve para depurar erorres antes de que redireccione
    /*
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        dd($validator->errors());
    }
    */

    //Cambiar los mensajes de error de los campos
    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'Debe tener un máximo de 20 caracteres.',
            'descripcion.max' => 'Debe tener un máximo de 50 caracteres.',
            'categoria_id.required' => 'Debe seleccionar una categoria.',
            'categoria_id.exists' => 'La categoria seleccionada no existe.',
        ];
    }

    public function attributes()
    {
        return [
            'img' => 'imagen'
        ];
    }
}
