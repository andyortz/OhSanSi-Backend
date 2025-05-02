<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id_grado';
    public $timestamps = false;

    protected $fillable = [
        'nombre_grado'
    ];
    public function niveles()
    {
        return $this->belongsToMany(NivelCategoria::class, 'grados_niveles', 'id_grado', 'id_nivel');
    }
    public function setNombreGradoAttribute($value)
    {
        $this->attributes['nombre_grado'] = strtoupper($value);
    }
}
