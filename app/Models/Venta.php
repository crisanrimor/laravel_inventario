<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['persona_id', 'user_id', 'subtotal', 'descuento', 'impuesto', 'total', 'estado'];

    public function productos(){
        return $this->belongsToMany(Producto::class)->withTimestamps()->withPivot('cantidad', 'precio');
    }

    public function cliente(){
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function inventarioMovimientos()
    {
        return $this->morphMany(InventarioMovimiento::class, 'source');
    }
}
