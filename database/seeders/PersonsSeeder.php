<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Persons\Models\Olimpista;
use Illuminate\Support\Facades\DB;

class PersonsSeeder extends Seeder
{
    public function run(): void
    {
        
        $persons = [
            [
                'person_ci' => 13752112,
                'names' => 'Alan',
                'surnames' => 'Turing',
                'email' => 'alan.turing@example.com',
                'birthdate' => '2006-06-23',
                'phone' => null
            ],
            [
                'person_ci' => 13752111,
                'names' => 'Ada',
                'surnames' => 'Lovelace',
                'email' => 'ada.lovelace@example.com',
                'birthdate' => '2006-12-10',
                'phone' => null
            ],
            [
                'person_ci' => 13752110,
                'names' => 'Linus',
                'surnames' => 'Torvalds',
                'email' => 'linus.torvalds@example.com',
                'birthdate' => '2006-12-28',
                'phone' => '12345678'
            ],
            [
                'person_ci' => 13752129,
                'names' => 'Tim',
                'surnames' => 'Berners-Lee',
                'email' => 'tim.bernerslee@example.com',
                'birthdate' => '2005-06-08',
                'phone' => '23456789'
            ],
            [
                'person_ci' => 13752128,
                'names' => 'Grace',
                'surnames' => 'Hopper',
                'email' => 'grace.hopper@example.com',
                'birthdate' => '2005-12-09',
                'phone' => null
            ],
            [
                'person_ci' => 13752127,
                'names' => 'Bill',
                'surnames' => 'Gates',
                'email' => 'bill.gates@example.com',
                'birthdate' => '2005-10-28',
                'phone' => '34567890'
            ],
            [
                'person_ci' => 13752126,
                'names' => 'Steve',
                'surnames' => 'Wozniak',
                'email' => 'steve.wozniak@example.com',
                'birthdate' => '2005-08-11',
                'phone' => '45678901'
            ],
            [
                'person_ci' => 13752125,
                'names' => 'Margaret',
                'surnames' => 'Hamilton',
                'email' => 'margaret.hamilton@example.com',
                'birthdate' => '2005-08-17',
                'phone' => null
            ],
            [
                'person_ci' => 13752124,
                'names' => 'Dennis',
                'surnames' => 'Ritchie',
                'email' => 'dennis.ritchie@example.com',
                'birthdate' => '2005-09-09',
                'phone' => null
            ],
            [
                'person_ci' => 13752123,
                'names' => 'Guido',
                'surnames' => 'van Rossum',
                'email' => 'guido.vanrossum@example.com',
                'birthdate' => '2005-01-31',
                'phone' => '56789012'
            ]
        ];
        DB::table('persons')->insert($persons);
    }
}
