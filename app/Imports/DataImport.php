<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;


class DataImport implements ToCollection, WithHeadingRow
{
    public Collection $rows;

    public function collection(Collection $collection)
    {
        $expected = [
            'nombres' => ['nombre', 'nombres', 'nombre_s', 'nombre__s'],
            'apellidos' => ['apellido', 'apellidos', 'apellido_s', 'apellido__s'],
            'ci_olimpista' => ['ci', 'ci_olimpista'],
            'fecha_de_nacimiento' => ['fecha_de_nacimiento', 'fecha_de_nacimiento', 'fecha_nacimiento'],
            'correo_electronico' => ['correo', 'correo_electronico', 'email'],
            'departamento' => ['departamento'],
            'provincia' => ['provincia'],
            'unidad_educativa' => ['unidad_educativa', 'colegio'],
            'grado' => ['grado'],
            'nombre_tutor' => ['nombre_tutor_legal', 'nombre_s_tutor_legal', 'nombre_tutor', 'nombre__s_tutor', 'nombre_tutor_legal'],
            'apellido_tutor' => ['apellido_tutor_legal', 'apellido_s_tutor_legal', 'apellido_tutor', 'apellido__s_tutor', 'apellido_tutor_legal'],
            'ci_tutor' => ['ci_tutor_legal', 'ci_tutor'],
            'celular_tutor' => ['celular', 'celular_tutor_legal', 'celular_tutor', 'telefono_tutor'],
            'correo_tutor' => ['correo_electronico_tutor_legal', 'correo_tutor_legal', 'correo_tutor', 'email_tutor'],
            'area' => ['area'],
            'nivel' => ['nivel', 'nivel_categoria'],
            'nombre_profesor' => ['nombre(s)_profesor','nombre_profesor', 'nombre_docente'],
            'apellido_profesor' => ['apellido(s)_profesor','apellido_profesor', 'apellido_docente'],
            'ci_profesor' => ['ci_profesor'],
            'celular_profesor' => ['celular_profesor', 'telefono_profesor'],
            'correo_profesor' => ['correo_electronico_profesor','correo_profesor', 'email_profesor']
        ];
        
        
        $firstRow = $collection->first();

        if (!$firstRow) {
            throw ValidationException::withMessages([
                'archivo' => ['El archivo está vacío o mal estructurado.']
            ]);
        }
    
        $actualHeaders = array_slice(array_keys($firstRow->toArray()), 0, 21);

    
        $normalized = fn($value) => trim(strtolower(Str::slug($value, ' ')));
    
        foreach ($expected as $key => $aliases) {
            $matched = false;
    
            foreach ($actualHeaders as $header) {
                if (in_array($normalized($header), array_map($normalized, $aliases))) {
                    $matched = true;
                    break;
                }
            }
    
            if (!$matched) {
                throw ValidationException::withMessages([
                    'formato' => ["Falta una columna válida para '$key'. Encabezados aceptados: " . implode(', ', $aliases)],
                    'recibidos' => $actualHeaders
                ]);
            }
        }
    
        $this->rows = $collection;
    }
}
