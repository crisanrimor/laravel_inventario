<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->can('editar-producto');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');

        return [
            'nombre' => 'required|max:30',
            'descripcion' => 'nullable|max:50',
            'sku' => 'required|min:5|max:10|unique:productos,sku,'.$producto->id,
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|file|mimes:jpg,png,webp|max:500'
        ];
    }
}
