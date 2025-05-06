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
                'ci_persona' => 13752112,
                'nombres' => 'Alan',
                'apellidos' => 'Turing',
                'correo_electronico' => 'alan.turing@example.com',
                'fecha_nacimiento' => '2006-06-23',
                'celular' => null
            ],
            [
                'ci_persona' => 13752111,
                'nombres' => 'Ada',
                'apellidos' => 'Lovelace',
                'correo_electronico' => 'ada.lovelace@example.com',
                'fecha_nacimiento' => '2006-12-10',
                'celular' => null
            ],
            [
                'ci_persona' => 13752110,
                'nombres' => 'Linus',
                'apellidos' => 'Torvalds',
                'correo_electronico' => 'linus.torvalds@example.com',
                'fecha_nacimiento' => '2006-12-28',
                'celular' => '12345678'
            ],
            [
                'ci_persona' => 13752129,
                'nombres' => 'Tim',
                'apellidos' => 'Berners-Lee',
                'correo_electronico' => 'tim.bernerslee@example.com',
                'fecha_nacimiento' => '2005-06-08',
                'celular' => '23456789'
            ],
            [
                'ci_persona' => 13752128,
                'nombres' => 'Grace',
                'apellidos' => 'Hopper',
                'correo_electronico' => 'grace.hopper@example.com',
                'fecha_nacimiento' => '2005-12-09',
                'celular' => null
            ],
            [
                'ci_persona' => 13752127,
                'nombres' => 'Bill',
                'apellidos' => 'Gates',
                'correo_electronico' => 'bill.gates@example.com',
                'fecha_nacimiento' => '2005-10-28',
                'celular' => '34567890'
            ],
            [
                'ci_persona' => 13752126,
                'nombres' => 'Steve',
                'apellidos' => 'Wozniak',
                'correo_electronico' => 'steve.wozniak@example.com',
                'fecha_nacimiento' => '2005-08-11',
                'celular' => '45678901'
            ],
            [
                'ci_persona' => 13752125,
                'nombres' => 'Margaret',
                'apellidos' => 'Hamilton',
                'correo_electronico' => 'margaret.hamilton@example.com',
                'fecha_nacimiento' => '2005-08-17',
                'celular' => null
            ],
            [
                'ci_persona' => 13752124,
                'nombres' => 'Dennis',
                'apellidos' => 'Ritchie',
                'correo_electronico' => 'dennis.ritchie@example.com',
                'fecha_nacimiento' => '2005-09-09',
                'celular' => null
            ],
            [
                'ci_persona' => 13752123,
                'nombres' => 'Guido',
                'apellidos' => 'van Rossum',
                'correo_electronico' => 'guido.vanrossum@example.com',
                'fecha_nacimiento' => '2005-01-31',
                'celular' => '56789012'
            ]
        ];
        DB::table('persona')->insert($personas);
    }
}
