<?php

namespace App\Modules\Persons\Controllers;
//OJITO
use App\Modules\Persons\Models\Person;

class PersonController
{
    public function getByCi($ci)
    {
        $person = Person::where('person_ci', $ci)->get();

        if ($person->isEmpty()) {
            return response()->json([
                'message' => 'La persona no esta registrada.',
                'ci' => $ci,
                'status' => 404
            ], 404);
        }

        return response()->json($person, 200);
    }
}
