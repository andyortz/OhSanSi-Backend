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
        'numero_celular',
        'correo_electronico',
        'fecha_nacimiento',
        'unidad_educativa',
        'id_grado',
        'id_provincia',
        'id_tutor'
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
    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }
    public function tutor()
    {
        return $this->belongsTo(Grado::class, 'id_tutor');
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
