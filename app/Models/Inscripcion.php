<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_olimpiada',
        'id_detalle_olimpista',
        'id_tutor_academico',
        'id_pago',
        'id_nivel',
        'estado',
        'fecha_inscripcion',
    ];

    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function detalleOlimpista()
    {
        return $this->belongsTo(DetalleOlimpista::class, 'id_detalle_olimpista', 'id_detalle_olimpista');
    }

    public function tutorAcademico()
    {
        return $this->belongsTo(Persona::class, 'id_tutor_academico', 'ci_persona');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel', 'id_nivel');
    }
}
