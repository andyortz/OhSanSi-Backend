<?php

namespace App\Imports;

use App\Models\Olimpista;
use App\Models\Colegio;
use App\Models\Grado;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OlimpistaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        foreach($collection as $row)
        {
            $unidadEducatia = $this->findUnidadEducativa($row[6]);
            $grado = $this->findGrado($row[7]);
            Olimpista::create([
                'nombres'=>$row[1],
                'apellidos'=>$row[2],
                'cedula_identidad'=>$row[3],
                'fecha_nacimiento'=>$row[4],
                'correo_electronico'=>$row[5],
                'unidad_educativa'=>$unidadEducatia,
                'id_grado'=>$grado
            ]);
        }    
    }
    public function findUnidadEducativa($unidadEducativa)
    {
        return Colegio::where('nombre_colegio', $unidadEducativa)->value('id_colegio');
    }
    public function findGrado($grado)
    {
        return Grado::where('nombre_grado', $grado)->value('id_grado');
    }
}
