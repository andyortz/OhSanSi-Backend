<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $primaryKey = 'ci_person';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ci_person',
        'names',
        'surnames',
        'email',
        'birthdate',
        'phone',
    ];

    // public function pagosRealizados()
    // {
    //     return $this->hasMany(Pago::class, 'id_responsable_inscripcion', 'ci_persona');
    // }

    // public function tutorAcademicoDeInscripciones()
    // {
    //     return $this->hasMany(Inscripcion::class, 'id_tutor_academico', 'ci_persona');
    // }

    // public function detalleOlimpista()
    // {
    //     return $this->hasOne(DetalleOlimpista::class, 'ci_olimpista', 'ci_persona');
    // }
}
