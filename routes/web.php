<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarioMovimientoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.status'])->prefix('dashboard')->name('dashboard.')->group(function(){
    Route::resource('/categorias', CategoriaController::class)->except(['create', 'edit', 'show']);
    Route::resource('/productos', ProductoController::class)->except(['create', 'edit', 'show']);
    Route::resource('/personas', PersonaController::class)->except(['create', 'edit', 'show']);
    Route::resource('/compras', CompraController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('/ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('/usuarios', UserController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/roles', RoleController::class)->except(['show']);

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/movimientos', [InventarioMovimientoController::class, 'index'])->name('movimientos');
    Route::get('/perfil/edit', [ProfileController::class, 'edit'])->name('perfil');
    Route::patch('/perfil/password', [ProfileController::class, 'changePassword'])->name('perfil.password');
    Route::patch('/perfil/imagen', [ProfileController::class, 'changeImage'])->name('perfil.imagen');
    Route::patch('/usuarios/{usuario}/estado', [UserController::class, 'updateEstado'])->name('usuarios.updateEstado');
});

Route::post('/api/search', function (Request $request) {
    $search = $request->input('search');
    $productos = Producto::where('nombre', 'LIKE', "%{$search}%")->orWhere('sku', 'LIKE', "%{$search}%")->limit(5)->get();
    return response()->json(['productos' => $productos]);
})->middleware('auth');

Route::middleware('guest')->group(function() {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('auth.login');
});

