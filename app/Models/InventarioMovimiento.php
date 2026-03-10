<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    protected $fillable = ['producto_id', 'type', 'quantity', 'user_id'];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
