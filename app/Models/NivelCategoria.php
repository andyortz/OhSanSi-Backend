<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelCategoria extends Model
{
    protected $table = 'niveles_categoria';
    protected $primaryKey = 'id_nivel';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }
    public function grados()
    {
        return $this->belongsToMany(Grado::class, 'grados_niveles', 'id_nivel', 'id_grado');
    }
    public function asociaciones()
    {
        return $this->hasMany(NivelAreaOlimpiada::class, 'id_nivel');
    }
}
