<?php

namespace App\Http\Controllers;

use App\Http\Requests\Compra\StoreRequest;
use App\Models\Compra;
use App\Models\Persona;
use App\Services\CompraService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class CompraController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-compra', only: ['index']),
            new Middleware('permission:crear-compra', only: ['create', 'store']),
            new Middleware('permission:mostrar-compra', only: ['show'])
        ];
    }

    public function __construct(private CompraService $compraService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('proveedor')->latest()->paginate(10);
        return Inertia::render('Compras/Index', [
            'breadcrumb' => [
                ['name' => 'Compras', 'url' => route('dashboard.compras.index')]
            ],
            'compras' => $compras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Persona::where('tipo_persona', 'proveedor')->get();

        return Inertia::render('Compras/Create', [
            'breadcrumb' => [
                ['name' => 'Compras', 'url' => route('dashboard.compras.index')],
                ['name' => 'Crear Compra', 'url' => route('dashboard.compras.create')]
            ],
            'proveedores' => $proveedores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        try{
            $this->compraService->finalizarCompra($data['proveedor_id'], $data['porcentaje_impuesto'], $data['productos']);
            return redirect()->route('dashboard.compras.index')->with('success', 'Compra realizada con éxito.');
        }catch(\Throwable $th){
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $proveedor = $compra->proveedor;
        $productos = $compra->productos()->latest()->paginate(10);

        return Inertia::render('Compras/Show', [
            'breadcrumb' => [
                ['name' => 'Compras', 'url' => route('dashboard.compras.index')],
                ['name' => 'Ver Compra', 'url' => route('dashboard.compras.show', $compra->id)]
            ],
            'compra' => $compra,
            'productos' => $productos,
            'proveedor' => $proveedor
        ]);
    }
}
