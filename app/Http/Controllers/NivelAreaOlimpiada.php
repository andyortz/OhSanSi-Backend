<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAreaOlimpiada extends Model
{
    protected $table = 'niveles_areas_olimpiadas';
    public $timestamps = false;

    protected $fillable = [
        'id_olimpiada',
        'id_area',
        'id_nivel',
    ];
}
