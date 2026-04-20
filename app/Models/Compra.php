<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = ['persona_id', 'user_id', 'subtotal', 'impuesto', 'total', 'estado'];

    public function productos(){
        return $this->belongsToMany(Producto::class)->withTimestamps()->withPivot('cantidad', 'precio');
    }

    public function proveedor(){
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function inventarioMovimientos()
    {
        return $this->morphMany(InventarioMovimiento::class, 'source');
    }
}
