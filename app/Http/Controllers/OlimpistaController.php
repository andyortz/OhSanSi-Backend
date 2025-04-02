<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;

class OlimpistaController extends Controller
{

    public function getByCedula($cedula)
    {
        $olimpista = Olimpista::where('cedula_identidad', $cedula)->first();
    
        return $olimpista 
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }
    
    public function getByEmail($email)
    {
        $olimpista = Olimpista::where('correo_electronico', $email)->first();
    
        return $olimpista
            ? response()->json($olimpista)
            : response()->json(['message' => 'No encontrado'], 404);
    }
}