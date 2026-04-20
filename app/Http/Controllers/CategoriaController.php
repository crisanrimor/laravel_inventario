<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoria\StoreRequest;
use App\Http\Requests\Categoria\UpdateRequest;
use App\Models\Categoria;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class CategoriaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-categoria', only: ['index']),
            new Middleware('permission:crear-categoria', only: ['store']),
            new Middleware('permission:editar-categoria', only: ['update']),
            new Middleware('permission:eliminar-categoria', only: ['destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::latest()->paginate(10);
        return Inertia::render('Categorias/Index', [
            'breadcrumb' => [
                ['name' => 'Categorias', 'url' => route('dashboard.categorias.index')]
            ],
            'categorias' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Categoria::create($request->validated());
        return back()->with('success', 'Categoria creada con éxito.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());
        return back()->with('success', 'Categoria actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return back()->with('success', 'Categoria eliminada con éxito.');
    }
}
