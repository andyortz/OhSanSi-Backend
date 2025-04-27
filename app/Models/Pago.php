<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'comprobante',
        'fecha_pago',
        'id_responsable_inscripcion', 
        'monto_pagado',
        'verificado',
        'verificado_en',
        'verificado_por',
    ];
    
    public function responsable()
    {
        return $this->belongsTo(Persona::class, 'id_responsable_inscripcion', 'ci_persona');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_pago', 'id_pago');
    }
}
