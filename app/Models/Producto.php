<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'sku', 'precio', 'costo', 'img_path', 'stock_minimo', 'stock_actual', 'categoria_id', 'persona_id'];    

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function movimientos(){
        return $this->hasMany(InventarioMovimiento::class);
    }
}
