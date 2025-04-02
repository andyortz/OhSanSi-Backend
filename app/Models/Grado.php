<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id_grado';
    public $timestamps = false;

    protected $fillable = [
        'nombre_grado',
        'nivel_academico',
        'orden'
    ];

    public function setnombreGradoAttribute($value)
    {
        $this->attributes['nombre_grado'] = strtoupper($value);
    }
    public function setnivelAcademicoAttribute($value)
    {
        $this->attributes['nivel_academico'] = strtoupper($value);
    }

}
