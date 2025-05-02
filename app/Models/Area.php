<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas_competencia';
    protected $primaryKey = 'id_area';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function asociaciones()
    {
        return $this->hasMany(NivelAreaOlimpiada::class, 'id_area');
    }
}
