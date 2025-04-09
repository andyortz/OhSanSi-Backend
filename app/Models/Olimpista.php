<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Olimpista extends Model
{
    protected $table = 'olimpistas';
    protected $primaryKey = 'id_olimpista';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula_identidad',
        'correo_electronico',
        'fecha_nacimiento',
        'unidad_educativa',
        'id_grado',
    ];

    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = strtoupper($value);
    }

    public function setApellidosAttribute($value)
    {
        $this->attributes['apellidos'] = strtoupper($value);
    }

    public function setUnidadEducativaAttribute($value)
    {
        $this->attributes['unidad_educativa'] = strtoupper($value);
    }
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_olimpista');
    }
    public function parentescos()
    {
        return $this->hasMany(Parentesco::class, 'id_olimpista');
    }
}
