<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    //
    protected $table = 'olimpistas';
    public $timestamps = false;
    protected $primaryKey = 'id_olimpista';
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
    public function setapellidosAttribute($value)
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
    //
}
