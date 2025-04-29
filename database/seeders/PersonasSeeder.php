<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Olimpista;
use Illuminate\Support\Facades\DB;

class PersonasSeeder extends Seeder
{
    public function run(): void
    {
        
        $personas = [
            [
                'ci_persona' => 1,
                'nombres' => 'Alan',
                'apellidos' => 'Turing',
                'correo_electronico' => 'alan.turing@example.com',
                'fecha_nacimiento' => '1912-06-23',
                'celular' => null
            ],
            [
                'ci_persona' => 2,
                'nombres' => 'Ada',
                'apellidos' => 'Lovelace',
                'correo_electronico' => 'ada.lovelace@example.com',
                'fecha_nacimiento' => '1815-12-10',
                'celular' => null
            ],
            [
                'ci_persona' => 3,
                'nombres' => 'Linus',
                'apellidos' => 'Torvalds',
                'correo_electronico' => 'linus.torvalds@example.com',
                'fecha_nacimiento' => '1969-12-28',
                'celular' => '12345678'
            ],
            [
                'ci_persona' => 4,
                'nombres' => 'Tim',
                'apellidos' => 'Berners-Lee',
                'correo_electronico' => 'tim.bernerslee@example.com',
                'fecha_nacimiento' => '1955-06-08',
                'celular' => '23456789'
            ],
            [
                'ci_persona' => 5,
                'nombres' => 'Grace',
                'apellidos' => 'Hopper',
                'correo_electronico' => 'grace.hopper@example.com',
                'fecha_nacimiento' => '1906-12-09',
                'celular' => null
            ],
            [
                'ci_persona' => 6,
                'nombres' => 'Bill',
                'apellidos' => 'Gates',
                'correo_electronico' => 'bill.gates@example.com',
                'fecha_nacimiento' => '1955-10-28',
                'celular' => '34567890'
            ],
            [
                'ci_persona' => 7,
                'nombres' => 'Steve',
                'apellidos' => 'Wozniak',
                'correo_electronico' => 'steve.wozniak@example.com',
                'fecha_nacimiento' => '1950-08-11',
                'celular' => '45678901'
            ],
            [
                'ci_persona' => 8,
                'nombres' => 'Margaret',
                'apellidos' => 'Hamilton',
                'correo_electronico' => 'margaret.hamilton@example.com',
                'fecha_nacimiento' => '1936-08-17',
                'celular' => null
            ],
            [
                'ci_persona' => 9,
                'nombres' => 'Dennis',
                'apellidos' => 'Ritchie',
                'correo_electronico' => 'dennis.ritchie@example.com',
                'fecha_nacimiento' => '1941-09-09',
                'celular' => null
            ],
            [
                'ci_persona' => 10,
                'nombres' => 'Guido',
                'apellidos' => 'van Rossum',
                'correo_electronico' => 'guido.vanrossum@example.com',
                'fecha_nacimiento' => '1956-01-31',
                'celular' => '56789012'
            ]
        ];
        DB::table('personas')->insert($personas);
    }
}
