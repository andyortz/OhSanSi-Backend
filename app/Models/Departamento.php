<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public $timestamps = false;

    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';

    protected $fillable = ['nombre_departamento'];
}

