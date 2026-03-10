<?php

namespace App\Models;

use App\Enums\TipoPersona;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono', 'direccion', 'tipo_persona'];

    protected $casts = [
        'tipo_persona' => TipoPersona::class,
    ];
}
