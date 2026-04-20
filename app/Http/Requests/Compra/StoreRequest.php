<?php

namespace App\Http\Requests\Compra;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('crear-compra');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proveedor_id' => 'required|exists:personas,id,tipo_persona,proveedor',
            'porcentaje_impuesto' => 'nullable|integer|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.costo' => 'required|numeric|min:1'
        ];
    }

    public function attributes()
    {
        return [
            'proveedor_id' => 'proveedor'
        ];
    }

    public function messages()
    {
        return [
            'productos.required' => 'No has agregado productos a la compra.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'porcentaje_impuesto' => $this->porcentaje_impuesto ?? 0,
        ]);
    }
}
