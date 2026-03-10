<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\InventarioMovimiento;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with('categoria')->paginate(10);
        return view('productos.index', ['title' => 'Productos', 'productos' => $productos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', ['title' => 'Crear Producto', 'categorias' => $categorias]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();
        $data['persona_id'] = 1;
        $path = null;

        DB::beginTransaction();

        try {
            if($request->hasFile('img')){
                $path = $request->file('img')->store('productos', 'public');
                $data['img_path'] = $path;
            }

            $producto = Producto::create($data);
            
            if($data['stock_actual'] > 0){
                InventarioMovimiento::create([
                    'producto_id' => $producto->id,
                    'type' => 1,
                    'quantity' => $data['stock_actual'],
                    'user_id' => 1
                ]);
            }

            DB::commit();
            return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
        }catch (\Throwable $th) {
            DB::rollBack();
            if ($path) Storage::disk('public')->delete($path);
            return redirect()->route('productos.index')->withErrors(['error' => 'Ocurrió un error al registrar el producto.']);
        }        
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
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', ['title' => 'Editar Producto', 'producto' => $producto, 'categorias' => $categorias]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();
        $data['img_path'] = $producto->img_path;

        if($request->hasFile('img')){
            $path = $request->file('img')->store('productos', 'public');
            $data['img_path'] = $path;

            if($producto->img_path && Storage::disk('public')->exists($producto->img_path)){
                Storage::disk('public')->delete($producto->img_path);
            }
        }

        $producto->update($data);

        if (!$producto->wasChanged()) {
            return redirect()->route('productos.index')->with('warning', 'No se realizó ningún cambio.');
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        try{
            if($producto->img_path && Storage::disk('public')->exists($producto->img_path)){
                Storage::disk('public')->delete($producto->img_path);
            }

            $producto->delete();
            
            return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito.');
        }catch(Throwable $e){
            return redirect()->route('productos.index')->withErrors(['error' => 'Ocurrió un error al eliminar el producto.']);
        }
    }
}
