<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'sku', 'precio', 'costo', 'img_path', 'stock_minimo', 'stock_actual', 'categoria_id'];
    protected $appends = ['imagen_url'];

    protected static function booted()
    {
        static::creating(function ($producto) {
            if (empty($producto->sku)) {

                $categoria = Categoria::find($producto->categoria_id);
                $prefix = $categoria ? strtoupper(substr($categoria->nombre, 0, 3)) : 'PROD';

                do {
                    $sku = $prefix . '-' . strtoupper(Str::random(5));
                } while (self::where('sku', $sku)->exists());

                $producto->sku = $sku;
            }
        });
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function movimientos(){
        return $this->hasMany(InventarioMovimiento::class);
    }

    public function getImagenUrlAttribute()
    {
        return $this->img_path ? Storage::url($this->img_path) : Storage::url('productos/noimage.png');
    }
}
