<?php

namespace App\Imports;

use App\Models\Tutor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TutoresImport implements ToCollection
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
            Tutor::create([
                'nombres'=>$row[10],
                'apellidos'=>$row[11],
                'ci'=>$row[12],
                'celular'=>$row[13],
                'correo_electronico'=>$row[14],
                'rol_parentesco'=>$row[15],
            ]);
        }
    }
}
