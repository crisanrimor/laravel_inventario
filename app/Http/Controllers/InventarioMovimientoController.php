<?php

namespace App\Http\Controllers;

use App\Models\InventarioMovimiento;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class InventarioMovimientoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-movimiento', only: ['index'])
        ];
    }

    public function index(){
        $movimientos = InventarioMovimiento::with('producto')->has('producto')->latest()->paginate(10);

        return Inertia::render('Movimientos/Index', [
            'breadcrumb' => [
                ['name' => 'Movimientos', 'url' => route('dashboard.movimientos')]
            ],
            'movimientos' => $movimientos
        ]);
    }
}
