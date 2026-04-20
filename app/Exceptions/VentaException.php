<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class VentaException extends Exception
{
    public function report(): void
    {
        Log::warning('Error al realizar venta', [
            'mensaje'    => $this->getMessage(),
            'usuario_id' => auth()->id(),
        ]);
    }
}
