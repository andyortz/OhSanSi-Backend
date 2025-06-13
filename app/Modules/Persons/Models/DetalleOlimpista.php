<?php

namespace App\Modules\Persons\Modules;

use Illuminate\Database\Eloquent\Model;

class DetalleOlimpista extends Model
{
    protected $table = 'detalle_olimpista';
    protected $primaryKey = 'id_detalle_olimpista';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_olimpiada',
        'ci_olimpista',
        'id_grado',
        'unidad_educativa',
        'ci_tutor_legal',
    ];

    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function olimpista()
    {
        return $this->belongsTo(Persona::class, 'ci_olimpista', 'ci_persona');
    }

    public function tutorLegal()
    {
        return $this->belongsTo(Persona::class, 'ci_tutor_legal', 'ci_persona');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_detalle_olimpista', 'id_detalle_olimpista');
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class, 'unidad_educativa', 'id_colegio');
    }
}
