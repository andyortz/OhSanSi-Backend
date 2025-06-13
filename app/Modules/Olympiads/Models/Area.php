<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area_competencia';
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
