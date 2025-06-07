<?php

namespace App\Services\Registers;

use App\Modules\Olympist\Models\Person;
use Illuminate\Support\Facades\DB;

class PersonService
{
    public static function register(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            $person = new Person();
            
            $person->names = $data['names'];
            $person->surnames = $data['surnames'];
            $person->ci_person = $data['ci_person'];
            $person->phone = $data['phone'] ?? null;
            $person->email = $data['email'];
            $person->birthdate = $data['birthdate'] ?? null;;
            
            $person->save();

            return $person;
        });
    }
}