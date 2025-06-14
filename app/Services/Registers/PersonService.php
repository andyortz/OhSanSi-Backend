<?php

namespace App\Services\Registers;

use App\Modules\Persons\Models\Person;
use Illuminate\Support\Facades\DB;

class PersonService
{
    public static function register(array $data): Person
    {
        return DB::transaction(function () use ($data) {
            $person = new Person();
            
            $person->names = $data['names'];
            $person->surnames = $data['surnames'];
            $person->person_ci = $data['ci'];
            $person->phone = $data['phone'] ?? null;
            $person->email = $data['email'];
            
            $person->save();

            return $person;
        });
    }
}
