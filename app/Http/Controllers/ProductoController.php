<?php

namespace App\Http\Controllers;

use App\Http\Requests\Producto\StoreRequest;
use App\Http\Requests\Producto\UpdateRequest;
use App\Models\Categoria;
use App\Models\InventarioMovimiento;
use App\Models\Producto;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-producto', only: ['index']),
            new Middleware('permission:crear-producto', only: ['store']),
            new Middleware('permission:editar-producto', only: ['update']),
            new Middleware('permission:eliminar-producto', only: ['destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::latest()->paginate(10);
        $categorias = Categoria::all();

        return Inertia::render('Productos/Index', [
            'breadcrumb' => [
                ['name' => 'Productos', 'url' => route('dashboard.productos.index')]
            ],
            'productos' => $productos,
            'categorias' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $path = null;

        DB::beginTransaction();

        try{
            if($request->hasFile('imagen')){
                $path = $request->file('imagen')->store('productos', 'public');
                $data['img_path'] = $path;
            }

            $producto = Producto::create($data);

            if(isset($data['stock_actual']) && $data['stock_actual'] > 0){
                InventarioMovimiento::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $data['stock_actual'],
                    'user_id' => 1
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.productos.index')->with('success', 'Producto creado con éxito.');
        }catch(\Throwable $th){
            DB::rollBack();
            if ($path) Storage::disk('public')->delete($path);
            return redirect()->route('dashboard.productos.index')->with('error', 'Ocurrió un error al crear el producto.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Producto $producto)
    {
        $data = $request->validated();
        $data['img_path'] = $producto->img_path;

        if($request->hasFile('imagen')){
            $path = $request->file('imagen')->store('productos', 'public');
            $data['img_path'] = $path;

            if($producto->img_path && Storage::disk('public')->exists($producto->img_path)){
                Storage::disk('public')->delete($producto->img_path);
            }
        }

        $producto->update($data);

        if (!$producto->wasChanged()) {
            return redirect()->route('dashboard.productos.index')->with('warning', 'No se realizó ningún cambio.');
        }

        return redirect()->route('dashboard.productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        if($producto->img_path && Storage::disk('public')->exists($producto->img_path)){
            Storage::disk('public')->delete($producto->img_path);
        }

        $producto->delete();

        return redirect()->route('dashboard.productos.index')->with('success', 'Producto eliminado con éxito.');
    }
}
