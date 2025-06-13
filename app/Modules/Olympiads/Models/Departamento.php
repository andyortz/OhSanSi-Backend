<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public $timestamps = false;

    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';

    protected $fillable = ['nombre_departamento'];


    public function setNombreDepartementoAttribute($value)
    {
        $this->attributes['nombre_departamento'] = strtoupper($value);
    }

}

