<?php

namespace App\Modules\Enrollments\Models;

use Illuminate\Database\Eloquent\Model;

class ListaInscripcion extends Model
{
    protected $table = 'lista_inscripcion';
    protected $primaryKey = 'id_lista';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'estado',
        'id_olimpiada',
        'ci_responsable_inscripcion',
        'fecha_creacion_lista',
    ];
    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }
    public function responsable()
    {
        return $this->belongsTo(Persona::class, 'id_responsable_inscripcion', 'ci_persona');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_lista', 'id_lista');
    }
}
