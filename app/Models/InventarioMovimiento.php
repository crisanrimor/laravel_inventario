<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    protected $fillable = ['producto_id', 'tipo', 'cantidad', 'user_id'];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
