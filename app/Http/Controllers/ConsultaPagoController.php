<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OCR\ConsultaPagoService;

class ConsultaPagoController extends Controller
{
    protected $consultaPago;

    public function __construct(ConsultaPagoService $consultaPago)
    {
        $this->consultaPago = $consultaPago;
    }

    public function verificarPorCi(string $ci)
    {
        $resultado = $this->consultaPago->tienePagos($ci);

        return response()->json($resultado);
    }
}
