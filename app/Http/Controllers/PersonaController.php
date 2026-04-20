<?php

namespace App\Http\Controllers;

use App\Http\Requests\Persona\StoreUpdateRequest;
use App\Models\Persona;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class PersonaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-persona', only: ['index']),
            new Middleware('permission:crear-persona', only: ['store']),
            new Middleware('permission:editar-persona', only: ['update']),
            new Middleware('permission:eliminar-persona', only: ['destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::latest()->paginate(10);
        return Inertia::render('Personas/Index', [
            'breadcrumb' => [
                ['name' => 'Personas', 'url' => route('dashboard.personas.index')]
            ],
            'personas' => $personas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateRequest $request)
    {
        $data = $request->validated();
        Persona::create($data);
        return redirect()->route('dashboard.personas.index')->with('success', 'Persona creada con éxito.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, Persona $persona)
    {
        $persona->update($request->validated());

        if(!$persona->wasChanged()){
            return redirect()->route('dashboard.personas.index')->with('warning', 'No se realizó ningún cambio.');
        }

        return redirect()->route('dashboard.personas.index')->with('success', 'Persona actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();
        return redirect()->route('dashboard.personas.index')->with('success', 'Persona eliminada con éxito.');
    }
}
