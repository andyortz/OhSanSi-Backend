<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    //
    protected $table = 'tutores';
    public $timestamps = false;

    protected $primaryKey = 'id_tutor';
    protected $fillable =[
        'nombres',
        'apellidos',
        'ci',
        'celular',
        'correo_electronico',
        'rol_parentesco'
    ];

    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = strtoupper($value);
    }

    public function setApellidosAttribute($value)
    {
        $this->attributes['apellidos'] = strtoupper($value);
    }

    public function olimpistas()
    {
        return $this->hasMany(Olimpista::class, 'id_tutor');
    }
}
