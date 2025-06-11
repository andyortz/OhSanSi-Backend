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
            'Nombres' => ['nombre', 'nombres'],
            'Apellidos' => ['apellido', 'apellidos'],
            'CI de olimpista' => ['Ci', 'ci_olimpista'],
            'Fecha de Nacimiento' => ['fecha_de_nacimiento', 'fecha_nacimiento'],
            'Correo electronico' => ['correo', 'correo_electronico', 'email'],
            'Departamento' => ['departamento'],
            'Provincia' => ['provincia'],
            'Unidad Educativa' => ['unidad_educativa', 'colegio'],
            'Grado' => ['grado'],
            'Nombre de tutor' => ['nombre_tutor', 'nombre_tutor_legal'],
            'Apellido de tutor' => ['apellido_tutor_legal', 'apellido_tutor'],
            'CI del tutor' => ['ci_tutor_legal', 'ci_tutor'],
            'Celular del tutor' => ['celular', 'celular_tutor_legal', 'celular_tutor', 'telefono_tutor'],
            'Correo del tutor' => ['correo_electronico_tutor_legal', 'correo_tutor_legal', 'correo_tutor', 'email_tutor'],
            'Area' => ['area'],
            'Nivel' => ['nivel', 'nivel_categoria'],
            'Nombre del profesor' => ['nombre(s)_profesor','nombre_profesor', 'nombre_docente'],
            'Apellido del profesor' => ['apellido(s)_profesor','apellido_profesor', 'apellido_docente'],
            'CI del profesor' => ['ci_profesor'],
            'Celular del profesor' => ['celular_profesor', 'telefono_profesor'],
            'Correo del profesor' => ['correo_electronico_profesor','correo_profesor', 'email_profesor']
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
            $clean = fn($text) => Str::title(str_replace('_', ' ', $text));
            if (!$matched) {
                throw ValidationException::withMessages([
                    'formato' => ["Falta una columna válida para '$key'. Encabezados aceptados: " . implode(', ', array_map($clean, $aliases))],
                    'recibidos' => array_map($clean, $actualHeaders)
                ]);
            }
        }
    
        $this->rows = $collection;
    }
}
