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
        'id_olimpista',
        'id_nivel',
        'id_pago',
        'fecha_inscripcion',
        'estado',
    ];

    public function olimpista()
    {
        return $this->belongsTo(Olimpista::class, 'id_olimpista');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel');
    }

    public function pago()
    {
        return $this->belongsTo(Pagos::class, 'id_pago');
    }
}
