<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InscripcionesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        foreach($collection as $row)
        {
            // dd($row);
            // $inscripcion = new Inscripcion();
            // $inscripcion->nombre = $row[0];
            // $inscripcion->apellido = $row[1];
            // $inscripcion->email = $row[2];
            // $inscripcion->telefono = $row[3];
            // $inscripcion->save();
        }
    }
}
