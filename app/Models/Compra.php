<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = ['persona_id', 'user_id', 'subtotal', 'impuesto', 'total', 'estado'];
}
