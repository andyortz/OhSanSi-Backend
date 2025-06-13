<?php

namespace App\Modules\Persons\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'ci_persona';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ci_persona',
        'nombres',
        'apellidos',
        'correo_electronico',
        'fecha_nacimiento',
        'celular',
    ];

    public function pagosRealizados()
    {
        return $this->hasMany(Pago::class, 'id_responsable_inscripcion', 'ci_persona');
    }

    public function tutorAcademicoDeInscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_tutor_academico', 'ci_persona');
    }

    public function detalleOlimpista()
    {
        return $this->hasOne(DetalleOlimpista::class, 'ci_olimpista', 'ci_persona');
    }
}
