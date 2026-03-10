<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::paginate(10);
        return view('categorias.index', ['title' => 'Categorias', 'categorias' => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create', ['title' => 'Crear Categoria']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|max:20|string',
            'descripcion' => 'nullable|string|max:100'
        ],
        [
            'nombre.required' => 'El campo nombre es requerido.',
            'nombre.max' => 'El nombre debe contener máximo 20 carácteres.'
        ]);

        Categoria::create($data);
    
        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', ['title' => 'Editar Categoria', 'categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre' => 'required|max:20|string',
            'descripcion' => 'nullable|string|max:100'
        ],
        [
            'nombre.required' => 'El campo nombre es requerido.',
            'nombre.max' => 'El nombre debe contener máximo 20 carácteres.'
        ]);

        $categoria->update($data);
        
        return redirect()->route('categorias.index')->with('success', 'Categoria editada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria eliminada con éxito.');
    }
}
