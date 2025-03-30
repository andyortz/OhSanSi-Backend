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
    //
}
