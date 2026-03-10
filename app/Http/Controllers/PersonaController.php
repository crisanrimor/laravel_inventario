<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Persona;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::paginate(10);
        return view('personas.index', ['title' => 'Personas', 'personas' => $personas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personas.create', ['title' => 'Crear Persona']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        Persona::create($request->validated());
        return redirect()->route('personas.index')->with('success', 'Persona creada con éxito.');
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
    public function edit(Persona $persona)
    {   
        return view('personas.edit', ['title' => 'Editar Persona', 'persona' => $persona]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        $persona->update($request->validated());

        if(!$persona->wasChanged()){
            return redirect()->route('personas.index')->with('warning', 'No se realizó ningún cambio.');
        }

        return redirect()->route('personas.index')->with('success', 'Persona editada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();
        return redirect()->route('personas.index')->with('success', 'Persona eliminada con éxito.');
    }
}
