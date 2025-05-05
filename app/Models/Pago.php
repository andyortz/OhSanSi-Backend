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
        'monto_pagado',
        'verificado',
        'verificado_en',
        'verificado_por',
        'id_lista'
    ];
    
    public function responsable()
    {
        return $this->belongsTo(ListaInscripcion::class, 'id_lista', 'ci_persona');
    }

}