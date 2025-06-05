<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Olimpista;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        
        $people = [
            [
                'ci_person' => 13752112,
                'names' => 'Alan',
                'surnames' => 'Turing',
                'email' => 'alan.turing@example.com',
                'birthdate' => '2006-06-23',
                'phone' => null
            ],
            [
                'ci_person' => 13752111,
                'names' => 'Ada',
                'surnames' => 'Lovelace',
                'email' => 'ada.lovelace@example.com',
                'birthdate' => '2006-12-10',
                'phone' => null
            ],
            [
                'ci_person' => 13752110,
                'names' => 'Linus',
                'surnames' => 'Torvalds',
                'email' => 'linus.torvalds@example.com',
                'birthdate' => '2006-12-28',
                'phone' => '12345678'
            ],
            [
                'ci_person' => 13752129,
                'names' => 'Tim',
                'surnames' => 'Berners-Lee',
                'email' => 'tim.bernerslee@example.com',
                'birthdate' => '2005-06-08',
                'phone' => '23456789'
            ],
            [
                'ci_person' => 13752128,
                'names' => 'Grace',
                'surnames' => 'Hopper',
                'email' => 'grace.hopper@example.com',
                'birthdate' => '2005-12-09',
                'phone' => null
            ],
            [
                'ci_person' => 13752127,
                'names' => 'Bill',
                'surnames' => 'Gates',
                'email' => 'bill.gates@example.com',
                'birthdate' => '2005-10-28',
                'phone' => '34567890'
            ],
            [
                'ci_person' => 13752126,
                'names' => 'Steve',
                'surnames' => 'Wozniak',
                'email' => 'steve.wozniak@example.com',
                'birthdate' => '2005-08-11',
                'phone' => '45678901'
            ],
            [
                'ci_person' => 13752125,
                'names' => 'Margaret',
                'surnames' => 'Hamilton',
                'email' => 'margaret.hamilton@example.com',
                'birthdate' => '2005-08-17',
                'phone' => null
            ],
            [
                'ci_person' => 13752124,
                'names' => 'Dennis',
                'surnames' => 'Ritchie',
                'email' => 'dennis.ritchie@example.com',
                'birthdate' => '2005-09-09',
                'phone' => null
            ],
            [
                'ci_person' => 13752123,
                'names' => 'Guido',
                'surnames' => 'van Rossum',
                'email' => 'guido.vanrossum@example.com',
                'birthdate' => '2005-01-31',
                'phone' => '56789012'
            ]
        ];
        DB::table('person')->insert($people);
    }
}
