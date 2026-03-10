<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Persona;
use App\Models\Producto;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::paginate(10);
        return view('compras.index', ['title' => 'Compras', 'compras' => $compras]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = Persona::where('tipo_persona', 2)->get();
        return view('compras.create', ['title' => 'Crear Compra', 'personas' => $personas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'persona_id' => 'required|exists:personas,id'
        ], [
            'persona_id.required' => 'Selecciona un proveedor.',
            'persona_id.exists' => 'El proveedor seleccionado no existe.'
        ]);

        $compra = Compra::create([
            'persona_id' => $data['persona_id'],
            'user_id' => 1,
            'subtotal' => 0,
            'impuesto' => 0,
            'total' => 0
        ]);

        return redirect()->route('compras.show', $compra->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $productos = Producto::all();
        return view('compras.show', ['title' => 'Ver Compra', 'compra' => $compra, 'productos' => $productos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
