<?php

namespace App\Http\Controllers;

use App\Models\InventarioMovimiento;

class InventarioMovimientoController extends Controller
{
    public function index(){
        $movimientos = InventarioMovimiento::with('producto')->paginate(10);

        return view('movimientos.index', ['title' => 'Movimientos', 'movimientos' => $movimientos]);
    }
}
