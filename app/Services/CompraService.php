<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class CompraService{
    public function finalizarCompra($proveedor, $porcentaje_impuesto, $productos){
        $data_productos = [];
        $subtotal = 0;

        foreach($productos as $item){
            $data_productos[$item['id']] = [
                'cantidad' => $item['cantidad'],
                'precio' => $item['costo']
            ];

            $subtotal += $item['cantidad'] * $item['costo'];
        }

        // Calcular impuestos y total
        $impuestos = round(($subtotal * $porcentaje_impuesto) / 100, 2);
        $total = round($subtotal + $impuestos, 2);

        DB::beginTransaction();

        try {
            // Bloquear para evitar race conditions
            $productosIds = array_keys($data_productos);
            $productos_compra = Producto::whereIn('id', $productosIds)->lockForUpdate()->get()->keyBy('id');

            //Guardar compra
            $compra = Compra::create([
                'persona_id' => $proveedor,
                'user_id' => auth()->user()->id,
                'subtotal' => $subtotal,
                'impuesto' => $impuestos,
                'total' => $total,
                'estado' => 1
            ]);

            // Llenar la tabla pivote con los productos
            $compra->productos()->attach($data_productos);

            // Procesar cada producto
            foreach ($data_productos as $producto_id => $pivot) {
                $producto = $productos_compra[$producto_id];
                $cantidad_nueva = $pivot['cantidad'];
                $precio_nuevo = $pivot['precio'];

                // Calcular costo promedio ponderado
                $costo_promedio = $producto->stock_actual + $cantidad_nueva > 0 ? round((($cantidad_nueva * $precio_nuevo) + ($producto->stock_actual * $producto->costo)) / ($producto->stock_actual + $cantidad_nueva)) : $precio_nuevo;

                // Actualizar stock y costo del producto
                $producto->increment('stock_actual', $cantidad_nueva);
                $producto->costo = $costo_promedio;
                $producto->save();

                // Registrar movimiento de inventario
                $compra->inventarioMovimientos()->create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $cantidad_nueva,
                    'user_id' => auth()->user()->id
                ]);
            }

            DB::commit();
            return $compra;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
