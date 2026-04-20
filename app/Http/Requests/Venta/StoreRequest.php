<?php

namespace App\Http\Requests\Venta;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('crear-venta');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:personas,id,tipo_persona,cliente',
            'porcentaje_impuesto' => 'nullable|integer|min:0',
            'descuento' => 'nullable|integer|min:0|lt:subtotal',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:1'
        ];
    }

    public function attributes()
    {
        return [
            'cliente_id' => 'cliente'
        ];
    }

    public function messages()
    {
        return [
            'productos.required' => 'No has agregado productos a la compra.',
            'descuento.lt' => 'El descuento debe ser menor que el subtotal.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'descuento' => $this->descuento ?? 0,
            'porcentaje_impuesto' => $this->porcentaje_impuesto ?? 0,
        ]);
    }
}
