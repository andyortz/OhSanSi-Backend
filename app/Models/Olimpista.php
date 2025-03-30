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
        'numero_celular',
        'correo_electronico',
        'fecha_nacimiento',
        'unidad_educativa',
        'id_grado',
        'id_provincia',
        'id_tutor',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_olimpista');
    }
}
