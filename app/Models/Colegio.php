<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    //
    protected $table = 'colegios';
    protected $primaryKey = 'id_colegio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_colegio',
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre_colegio'] = strtoupper($value);
    }

    public function asociaciones()
    {
        return $this->hasMany(Olimpista::class, 'unidad_educativa');
    }
}
