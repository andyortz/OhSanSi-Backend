<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Olimpista;
use Illuminate\Support\Facades\DB;

class OlimpistasSeeder extends Seeder
{
    public function run(): void
    {
        
        $olimpistas =[
            ['nombres' => 'Deyson', 'apellidos' => 'Isa', 'cedula_identidad' => 8, 'fecha_nacimiento' => '2025-04-09', 'correo_electronico' => 'deysonIsa@gmail.com', 'unidad_educativa' => 1, 'id_grado' => 6],
            ['nombres' => 'Valeria', 'apellidos' => 'Méndez Rojas', 'cedula_identidad' => 987432101, 'fecha_nacimiento' => '2008-05-15', 'correo_electronico' => 'valeria.mendez@example.com', 'unidad_educativa' => 45, 'id_grado' => 8],
            ['nombres' => 'Carlos', 'apellidos' => 'Fernández López', 'cedula_identidad' => 125678909, 'fecha_nacimiento' => '2007-11-22', 'correo_electronico' => 'carlos.fernandez@example.com', 'unidad_educativa' => 12, 'id_grado' => 7],
            ['nombres' => 'Ana', 'apellidos' => 'Gutiérrez Paz', 'cedula_identidad' => 456781234, 'fecha_nacimiento' => '2009-03-30', 'correo_electronico' => 'ana.gutierrez@example.com', 'unidad_educativa' => 88, 'id_grado' => 5],
            ['nombres' => 'Luis', 'apellidos' => 'Torres Molina', 'cedula_identidad' => 781234567, 'fecha_nacimiento' => '2006-09-10', 'correo_electronico' => 'luis.torres@example.com', 'unidad_educativa' => 33, 'id_grado' => 10],
            ['nombres' => 'María', 'apellidos' => 'Vargas Salazar', 'cedula_identidad' => 892345678, 'fecha_nacimiento' => '2010-07-18', 'correo_electronico' => 'maria.vargas@example.com', 'unidad_educativa' => 67, 'id_grado' => 4],
            ['nombres' => 'Jorge', 'apellidos' => 'Díaz Herrera', 'cedula_identidad' => 901236789, 'fecha_nacimiento' => '2005-12-05', 'correo_electronico' => 'jorge.diaz@example.com', 'unidad_educativa' => 102, 'id_grado' => 11],
            ['nombres' => 'Lucía', 'apellidos' => 'Pérez Castro', 'cedula_identidad' => 234569012, 'fecha_nacimiento' => '2011-01-25', 'correo_electronico' => 'lucia.perez@example.com', 'unidad_educativa' => 56, 'id_grado' => 3],
            ['nombres' => 'Diego', 'apellidos' => 'Ríos Mendoza', 'cedula_identidad' => 345890123, 'fecha_nacimiento' => '2009-08-14', 'correo_electronico' => 'diego.rios@example.com', 'unidad_educativa' => 29, 'id_grado' => 6],
            ['nombres' => 'Sofía', 'apellidos' => 'López Arce', 'cedula_identidad' => 567012345, 'fecha_nacimiento' => '2007-04-20', 'correo_electronico' => 'sofia.lopez@example.com', 'unidad_educativa' => 91, 'id_grado' => 9],
            ['nombres' => 'Pablo', 'apellidos' => 'Martínez Flores', 'cedula_identidad' => 678901256, 'fecha_nacimiento' => '2008-10-08', 'correo_electronico' => 'pablo.martinez@example.com', 'unidad_educativa' => 74, 'id_grado' => 7]
        ];
        DB::table('olimpistas')->insert($olimpistas);
    }
}
