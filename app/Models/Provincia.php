<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    public $timestamps = false;

    protected $table = 'provincias';
    protected $primaryKey = 'id_provincia';

    protected $fillable = [
        'nombre_provincia',
        'id_departamento'
    ];


    public function setNombresProvinciaAttribute($value)
    {
        $this->attributes['nombre_provincia'] = strtoupper($value);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function asociaciones()
    {
        return $this->hasMany(Colegio::class, 'provincia');
    }
}
