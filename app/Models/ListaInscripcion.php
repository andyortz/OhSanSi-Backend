<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaInscripcion extends Model
{
    protected $table = 'lista_inscripcion';
    protected $primaryKey = 'id_lista';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'estado',
        'ci_responsable_inscripcion',
        'fecha_creacion_lista',
        'cantidad',
        'monto_total'
    ];

    public function responsable()
    {
        return $this->belongsTo(Persona::class, 'id_responsable_inscripcion', 'ci_persona');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_lista', 'id_lista');
    }
}
