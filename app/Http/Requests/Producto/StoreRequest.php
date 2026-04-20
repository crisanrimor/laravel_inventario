<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('crear-producto');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|max:30',
            'descripcion' => 'nullable|max:50',
            'sku' => 'nullable|min:5|max:10|unique:productos,sku',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer',
            'stock_actual' => 'nullable|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|file|mimes:jpg,png,webp|max:500'
        ];
    }

    public function attributes()
    {
        return [
            'categoria_id' => 'categoria'
        ];
    }

    protected function prepareForValidation()
    {
        //Se hace porque Inertia envía los campos vacíos como null y al insertar en BD dan error, se eliminan los campos para que la BD tome por default 0.
        $data = $this->all();

        if (is_null($data['stock_minimo'])) {
            unset($data['stock_minimo']);
        }

        if (is_null($data['stock_actual'])) {
            unset($data['stock_actual']);
        }

        $this->replace($data);
    }
}
