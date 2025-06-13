<?php

namespace App\Modules\Enrollments\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripcion';
    protected $primaryKey = 'id_inscripcion';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_detalle_olimpista',
        'ci_tutor_academico',
        'id_nivel',
        'id_lista',
    ];
    public function detalleOlimpista()
    {
        return $this->belongsTo(DetalleOlimpista::class, 'id_detalle_olimpista', 'id_detalle_olimpista');
    }

    public function tutorAcademico()
    {
        return $this->belongsTo(Persona::class, 'id_tutor_academico', 'ci_persona');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel', 'id_nivel');
    }
    public function lista()
    {
        return $this->belongsTo(ListaInscripcion::class, 'id_lista', 'id_lista');
    }
}
