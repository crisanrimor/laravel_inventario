<?php

namespace App\Services;

use App\Exceptions\VentaException;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function finalizarVenta($cliente, $porcentaje_impuesto, $descuento, $productos){
        $data_productos = [];
        $subtotal = 0;

        foreach($productos as $item){
            $data_productos[$item['id']] = [
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio']
            ];

            $subtotal += $item['cantidad'] * $item['precio'];
        }

        if($descuento >= $subtotal){
            throw new VentaException('El descuento no puede ser mayor al subtotal.');
        }

        // Calcular descuento e impuestos.
        $subtotal_descuento = $subtotal - $descuento;
        $impuestos = round(($subtotal_descuento * $porcentaje_impuesto) / 100, 2);
        $total = round($subtotal_descuento + $impuestos, 2);

        DB::beginTransaction();

        try{
            // Bloquear para evitar race conditions
            $productosIds = array_keys($data_productos);
            $productos_venta = Producto::whereIn('id', $productosIds)->lockForUpdate()->get()->keyBy('id');

            //Guardar venta
            $venta = Venta::create([
                'persona_id' => $cliente,
                'user_id' => auth()->user()->id,
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'impuesto' => $impuestos,
                'total' => $total,
                'estado' => 1
            ]);

            // Procesar los productos.
            foreach($data_productos as $producto_id => $pivot){
                $producto = $productos_venta[$producto_id];

                // Validar el stock actual.
                if($pivot['cantidad'] > $producto->stock_actual){
                    throw new VentaException("Stock insuficiente para: {$producto->nombre}");
                }

                // Actualizar stock
                $producto->decrement('stock_actual', $pivot['cantidad']);

                // Registrar movimiento de inventario
                $venta->inventarioMovimientos()->create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $pivot['cantidad'],
                    'user_id' => auth()->user()->id
                ]);
            }

            // Llenar la tabla pivote con los productos
            $venta->productos()->attach($data_productos);

            DB::commit();
            return $venta;
        }catch(\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }
}
