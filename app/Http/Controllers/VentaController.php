<?php

namespace App\Http\Controllers;

use App\Exceptions\VentaException;
use App\Http\Requests\Venta\StoreRequest;
use App\Models\Persona;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class VentaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-venta', only: ['index']),
            new Middleware('permission:crear-venta', only: ['create', 'store']),
            new Middleware('permission:mostrar-venta', only: ['show'])
        ];
    }

    public function __construct(private VentaService $ventaService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Venta::with('cliente')->latest()->paginate(10);
        return Inertia::render('Ventas/Index', [
            'breadcrumb' => [
                ['name' => 'Ventas', 'url' => route('dashboard.ventas.index')]
            ],
            'ventas' => $ventas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Persona::where('tipo_persona', 'cliente')->get();

        return Inertia::render('Ventas/Create', [
            'breadcrumb' => [
                ['name' => 'Ventas', 'url' => route('dashboard.ventas.index')],
                ['name' => 'Crear Venta', 'url' => route('dashboard.ventas.create')]
            ],
            'clientes' => $clientes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        try {
            $this->ventaService->finalizarVenta($data['cliente_id'], $data['porcentaje_impuesto'], $data['descuento'], $data['productos']);
            return redirect()->route('dashboard.ventas.index')->with('success', 'Venta realizada con éxito.');
        }catch(VentaException $e){
            return redirect()->route('dashboard.ventas.index')->with('error', $e->getMessage());
        }catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $cliente = $venta->cliente;
        $productos = $venta->productos()->latest()->paginate(10);

        return Inertia::render('Ventas/Show', [
            'breadcrumb' => [
                ['name' => 'Ventas', 'url' => route('dashboard.ventas.index')],
                ['name' => 'Ver Venta', 'url' => route('dashboard.ventas.show', $venta->id)]
            ],
            'cliente' => $cliente,
            'productos' => $productos,
            'venta' => $venta
        ]);
    }
}
