<?php

namespace App\Http\Controllers;

use App\Models\InventarioMovimiento;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        $hoy = Carbon::today();
        $ayer = Carbon::yesterday();

        $total_ventas_hoy = Venta::whereDate('created_at', $hoy)->sum('total');
        $total_ventas_ayer = Venta::whereDate('created_at', $ayer)->sum('total');

        if ($total_ventas_ayer > 0) {
            $porcentajeVentas = (($total_ventas_hoy - $total_ventas_ayer) / $total_ventas_ayer) * 100;
        } else {
            $porcentajeVentas = $total_ventas_hoy > 0 ? 100 : 0;
        }

        $total_clientes = Persona::where('tipo_persona', 'cliente')->count();
        $total_productos = Producto::all()->count();
        $valorInventario = Producto::sum(DB::raw('stock_actual * costo'));

        $productosMasVendidos = Producto::withSum(
            ['movimientos' => fn($q) => $q->where('tipo', 'salida')],
            'cantidad'
        )
        ->with('categoria')
        ->orderByDesc('movimientos_sum_cantidad')
        ->limit(5)
        ->get();

        $clientesFrecuentes = Venta::selectRaw('persona_id, COUNT(id) as total')
        ->with('cliente')
        ->groupBy('persona_id')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

        // Reporte ventas mensuales
        $consulta_ventas = Venta::selectRaw('MONTH(updated_at) as mes, COUNT(id) as total, SUM(total) as ganancias')
        ->whereYear('updated_at', now()->year)
        ->groupByRaw('MONTH(updated_at)')
        ->get()->keyBy('mes');

        // Repote entrada/salidas inventario
        $consulta_inventario = InventarioMovimiento::selectRaw('MONTH(updated_at) as mes, SUM(CASE WHEN tipo = "entrada" THEN cantidad ELSE 0 END) as total_entradas,
        SUM(CASE WHEN tipo = "salida" THEN cantidad ELSE 0 END) as total_salidas')
        ->whereYear('updated_at', now()->year)
        ->groupByRaw('MONTH(updated_at)')
        ->get()->keyBy('mes');

        $ganancias_totales = [];
        $inventario_control = [];

        for($i = 1; $i <= 12; $i++){
            $inventario_control[$i] = ["total_entradas" => $consulta_inventario[$i]->total_entradas ?? 0, "total_salidas" => $consulta_inventario[$i]->total_salidas ?? 0];
            $ganancias_totales[$i] = ["total" => $consulta_ventas[$i]->total ?? 0, "ganancias" => $consulta_ventas[$i]->ganancias ?? 0];
        }

        return Inertia::render('Home/Index', compact('total_ventas_hoy', 'porcentajeVentas', 'total_clientes', 'total_productos', 'valorInventario', 'ganancias_totales', 'inventario_control', 'productosMasVendidos', 'clientesFrecuentes'));
    }
}
