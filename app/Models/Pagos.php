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
        'id_olimpiada',
        'comprobante',
        'fecha_pago',
        'nombre_pagador',
        'monto_pagado',
        'verificado',
        'verificado_en',
        'verificado_por',
    ];

    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_pago');
    }
}
